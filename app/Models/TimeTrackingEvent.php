<?php

namespace App\Models;

use App\Casts\CoordinatesCast;
use Illuminate\Database\Eloquent\Model;

class TimeTrackingEvent extends Model
{
    protected $table = 'tb_time_tracking_events';

    protected $fillable = [
        'type',
        'batteryLevel',
        'longitude',
        'latitude',
    ];

    protected $casts = [
        'coordinates' => CoordinatesCast::class,
    ];
}
