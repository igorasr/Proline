<?php

namespace App\Models;

use App\Casts\CoordinatesCast;
use Illuminate\Database\Eloquent\Model;

class MediaBuffer extends Model
{
    protected $table = 'tb_media_buffers';

    protected $fillable = [
        'operationName', 'latitude', 'longitude', 'batteryLevel', 'status', 'image'
    ];

    protected $casts = [
        'coordinates' => CoordinatesCast::class,
    ];
}
