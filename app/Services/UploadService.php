<?php

namespace App\Services;

use App\Enums\UploadStatus;
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
        $import->markSuccess();
      })
      ->catch(function (Batch $batch, Throwable $e) use ($import){
        $import->markError();
      })
      ->finally(function (Batch $batch) use ($import) {
        $import->refresh();
      })
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
      'status' => UploadStatus::PENDING->value,
      'total_items' => $total,
      'processed_items' => 0,
      'meta' => ['path' => $filepath],
    ]);

    $this->dispatchChunks($data, $import);

    return $import;
  }

  // Method not in use
  public function reprocess(UploadHistory $import): UploadHistory
  {
    // reuse the stored file path
    $path = $import->meta['path'] ?? null;
    if (!$path || !Storage::exists($path)) {
      throw new \RuntimeException('Arquivo original não encontrado para reprocessamento.');
    }

    $content = Storage::get($path);
    $data = json_decode($content, true);

    if (!is_array($data)) {
      throw new \RuntimeException("Arquivo JSON inválido no reprocessamento.");
    }

    $import->update(['status' => 'pending', 'total_items' => count($data), 'processed_items' => 0, 'error_message' => null]);

    $this->dispatchChunks($data, $import);

    return $import;
  }
}
