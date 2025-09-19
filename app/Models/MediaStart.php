<?php

namespace App\Models;

use App\Casts\CoordinatesCast;
use Illuminate\Database\Eloquent\Model;

class MediaStart extends Model
{
    protected $table = 'tb_media_starts';

    protected $fillable = [
        'operationName', 'latitude', 'longitude', 'batteryLevel'
    ];

    protected $casts = [
        'coordinates' => CoordinatesCast::class,
    ];
}
