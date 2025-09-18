<?php

namespace App\Services;

use App\Jobs\ProcessUploadChunkJob;
use App\Models\UploadHistory;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class UploadService
{
  private function dispatchChunks(array $entries, UploadHistory $import): void
  {
    $jobs = [];
    foreach ($entries as $modelName => $entry) {
      $jobs[] = new ProcessUploadChunkJob($import->id, $modelName, $entry);
    }

    $batch = Bus::batch($jobs)
      ->name("import:{$import->id}")
      ->then(function (Batch $batch) use ($import){
        // TODO: All jobs completed successfully...
      })
      ->catch(function (Batch $batch, Throwable $e) use ($import){
        // TODO: First batch job failure detected...
      })
      ->finally(function (Batch $batch) use ($import) {

        $import->refresh();
      })
      ->allowFailures()
      ->dispatch();
  }

  public function create(string $filepath, string $originalName): UploadHistory
  {
    // Read file and decode JSON to count items (we assume it's an array at top-level)
    $content = Storage::get($filepath);
    $data = json_decode($content, true);
    if (!is_array($data)) {
      throw new \RuntimeException("Arquivo JSON inválido: conteúdo não é um array.");
    }

    $total = count($data);

    $import = UploadHistory::create([
      'filename' => $originalName,
      'mime' => 'application/json',
      'size' => Storage::size($filepath),
      'status' => 'pending',
      'total_items' => $total,
      'processed_items' => 0,
      'meta' => ['path' => $filepath],
    ]);

    $this->dispatchChunks($data, $import);


    return $import;
  }

  // Method not in use
  public function reprocess(UploadHistory $import, int $chunkSize = 500): UploadHistory
  {
    // reuse the stored file path
    $path = $import->meta['path'] ?? null;
    if (!$path || !Storage::exists($path)) {
      throw new \RuntimeException('Arquivo original não encontrado para reprocessamento.');
    }

    // optional: clear logs and reset counters
    $import->logs()->create([
      'level' => 'info',
      'message' => 'Reprocess triggered.'
    ]);

    $content = Storage::get($path);
    $data = json_decode($content, true);
    if (!is_array($data)) {
      throw new \RuntimeException("Arquivo JSON inválido no reprocessamento.");
    }

    // reset status/counters
    $import->update(['status' => 'pending', 'total_items' => count($data), 'processed_items' => 0, 'error_message' => null]);

    // dispatch batch as in createAndDispatch but with same import
    $chunks = array_chunk($data, $chunkSize);

    $this->dispatchChunks($chunks, $import);

    return $import;
  }
}
