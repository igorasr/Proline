<?php

namespace App\Jobs;

use App\Models\UploadHistory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;
use Illuminate\Support\Facades\Log;

class ProcessUploadChunkJob implements ShouldQueue
{
   use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $importId;
    public array $chunk;
    public int $index;

    public function __construct(int $importId, array $chunk)
    {
        $this->importId = $importId;
        $this->chunk = $chunk;
        $this->onQueue('imports'); // ensure it goes to imports queue
    }

    public function handle(): void
    {
        $import = UploadHistory::find($this->importId);
        if (!$import) {
            // bail out - nothing to do
            Log::warning("Import not found: {$this->importId}");
            return;
        }

        try {
            // mark processing if not already
            $import->markProcessing();

            // Process each record in chunk (example: create or update domain models)
            foreach ($this->chunk as $item) {

                $import->increment('processed_items');
            }
        } catch (Throwable $e) {
            Log::error('Chunk processing failed', ['import_id'=>$this->importId,'error'=>$e->getMessage()]);
            throw $e; // allow batch.catch to run
        }
    }

    public function failed(Throwable $exception): void
    {
        // mark import as error if job fails
        $import = UploadHistory::find($this->importId);
        if ($import) {
            $import->markError($exception->getMessage());
        }
        Log::error('ProcessUploadChunkJob failed', ['import_id'=>$this->importId,'error'=>$exception->getMessage()]);
    }
}
