<?php

namespace App\Enums;

enum UploadStatus: string
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case SUCCESS = 'success';
    case ERROR = 'error';

    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }
}