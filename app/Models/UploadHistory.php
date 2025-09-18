<?php

namespace App\Models;

use App\Enums\UploadStatus;
use Illuminate\Database\Eloquent\Model;

class UploadHistory extends Model
{
    protected $table = "tb_upload_history";
    
    protected $fillable = [
        'filename',
        'mime',
        'size',
        'status',
        'total_items',
        'processed_items',
        'meta',
        'error_message'
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    // helpers
    public function markProcessing(): void
    {
        $this->update(['status' => UploadStatus::PROCESSING->value]);
    }
    public function markSuccess(): void
    {
        $this->update(['status' => UploadStatus::SUCCESS->value]);
    }
    public function markError(string $message = ""): void
    {
        $this->update(['status' => UploadStatus::ERROR->value, 'error_message' => $message]);
    }
}
