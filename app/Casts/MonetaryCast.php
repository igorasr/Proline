<?php

namespace App\Casts;

use App\ValueObjects\Monetary;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class MonetaryCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (!isset($attributes[$key])) {
            throw new \InvalidArgumentException("The attribute '{$key}' is not present in the model.");
        }

        return new Monetary((float) $attributes[$key]);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (!$value instanceof Monetary) {
            throw new \InvalidArgumentException('The value must be an instance of App\ValueObjects\Monetary.');
        }

        return $value->value();
    }
}
