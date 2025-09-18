<?php

namespace App\Jobs;

use App\Enums\UploadStatus;
use App\Models\UploadHistory;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProcessUploadChunkJob implements ShouldQueue
{
   use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    public int $importId;
    public array $chunk;
    public string $modelName;
    public $timeout = 3600;

    public function __construct(int $importId, string $modelName, array $entries)
    {
        $this->importId = $importId;
        $this->chunk = $entries;
        $this->modelName = $modelName;
    }

    public function handle(): void
    {
        $import = UploadHistory::find($this->importId);
        if (!$import) {
            Log::warning("Import not found: {$this->importId}");
            return;
        }

        try {

            if($import->status !== UploadStatus::PROCESSING->value) {
              $import->markProcessing();
            }

            $class = 'App\\Models\\' . Str::singular($this->modelName);

            if (!class_exists($class)) {
                Log::warning("Model class not found: {$class}");
                return;
            }

            $model = app($class);
            
            foreach ($this->chunk as $item) {
                $model::create($item);
                $import->increment('processed_items');
            }
        } catch (Throwable $e) {
            Log::error('Chunk processing failed', ['import_id'=>$this->importId,'error'=>$e->getMessage()]);
            throw $e;
        }
    }

    public function failed(Throwable $exception): void
    {
        $import = UploadHistory::find($this->importId);
        if ($import) {
            $import->markError($exception->getMessage());
        }
        Log::error('ProcessUploadChunkJob failed', ['import_id'=>$this->importId,'error'=>$exception->getMessage()]);
    }
}
