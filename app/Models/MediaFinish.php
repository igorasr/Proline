<?php

namespace App\Models;

use App\Casts\CoordinatesCast;
use Illuminate\Database\Eloquent\Model;

class MediaFinish extends Model
{
    protected $table = 'tb_media_finishes';

    protected $fillable = [
        'operationName', 'latitude', 'longitude', 'batteryLevel'
    ];

    protected $casts = [
        'coordinates' => CoordinatesCast::class,
    ];
}
