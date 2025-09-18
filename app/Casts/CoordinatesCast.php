<?php

namespace App\Casts;

use App\ValueObjects\Coordinates;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class CoordinatesCast implements CastsAttributes
{
public function get($model, string $key, $value, array $attributes): Coordinates
    {
        // Espera colunas 'latitude' e 'longitude' no model
        if (!isset($attributes['latitude'], $attributes['longitude'])) {
            throw new InvalidArgumentException('Latitude/Longitude ausentes no model.');
        }

        return new Coordinates(
            (float) $attributes['latitude'],
            (float) $attributes['longitude']
        );
    }

    public function set($model, string $key, $value, array $attributes): array
    {
        if (!$value instanceof Coordinates) {
            throw new InvalidArgumentException('The value must be an instance of App\ValueObjects\Coordinates.');
        }

        return [
            'latitude'  => $value->latitude,
            'longitude' => $value->longitude,
        ];
    }
}
