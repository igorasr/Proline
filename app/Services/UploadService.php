<?php

namespace App\Services;

use App\Models\UploadHistory;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class UploadService
{
  private function dispatchChunks(array $chunks, UploadHistory $import): void
  {
    $jobs = [];
    // foreach ($chunks as $index => $chunk) {
    //     $jobs[] = new ProcessImportChunkJob($import->id, $chunk);
    // }

    $batch = Bus::batch($jobs)
      ->name("import:{$import->id}")
      ->then(function (Batch $batch) use ($import) {
        // all jobs successful
        try {
          $import->refresh();
          $import->markSuccess();
          $import->logs()->create([
            'level' => 'info',
            'message' => 'Import finished successfully.',
            'context' => json_encode(['batch_id' => $batch->id])
          ]);
        } catch (Throwable $e) {
          Log::error('Error marking import success: ' . $e->getMessage(), ['import_id' => $import->id]);
        }
      })
      ->catch(function (Batch $batch, Throwable $e) use ($import) {
        // batch had a failure
        $import->markError($e->getMessage());
        $import->logs()->create([
          'level' => 'error',
          'message' => 'Batch failed: ' . $e->getMessage(),
          'context' => json_encode(['batch_id' => $batch->id, 'trace' => $e->getTraceAsString()]),
        ]);
        Log::error('Import batch failed', ['import_id' => $import->id, 'error' => $e->getMessage()]);
      })
      ->finally(function (Batch $batch) use ($import) {
        // called always: update processed count from DB (defensive)
        $import->refresh();
      })
      ->dispatch();

    // mark processing after dispatch
    $import->markProcessing();

    // $import->logs()->create([
    //   'level' => 'info',
    //   'message' => 'Import queued.',
    //   'context' => json_encode(['batch_id' => $batch->id ?? null, 'chunks' => count($jobs)])
    // ]);
  }

  public function create(string $filepath, string $originalName, int $chunkSize = 500): UploadHistory
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

    // split in chunks
    $chunks = array_chunk($data, $chunkSize);

    $this->dispatchChunks($chunks, $import);

    return $import;
  }

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
