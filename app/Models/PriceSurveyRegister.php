<?php

namespace App\Models;

use App\Casts\CoordinatesCast;
use App\Casts\MonetaryCast;
use Illuminate\Database\Eloquent\Model;

class PriceSurveyRegister extends Model
{
    protected $table = 'tb_price_survey_registers';

    protected $fillable = [
        'operationName', 'skuName', 'latitude', 'longitude', 'batteryLevel', 'competing', 'category'    
    ];

    protected $casts = [
        'coordinates' => CoordinatesCast::class,
        'price' => MonetaryCast::class,
    ];
}
