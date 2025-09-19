<?php

namespace App\Models;

use App\Casts\CoordinatesCast;
use Illuminate\Database\Eloquent\Model;

class PriceSurveyStart extends Model
{
    protected $table = 'tb_price_survey_starts';

    protected $fillable = [
        'operationName', 'latitude', 'longitude', 'batteryLevel'
    ];

    protected $casts = [
        'coordinates' => CoordinatesCast::class,
    ];
}
