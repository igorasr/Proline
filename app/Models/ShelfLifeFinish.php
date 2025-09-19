<?php

namespace App\Models;

use App\Casts\CoordinatesCast;
use Illuminate\Database\Eloquent\Model;

class ShelfLifeFinish extends Model
{
    protected $table = 'tb_shelf_life_finishes';

    protected $fillable = [
        'operationName', 'latitude', 'longitude', 'batteryLevel'
    ];

    protected $casts = [
        'coordinates' => CoordinatesCast::class,
    ];
}
