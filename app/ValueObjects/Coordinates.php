<?php

namespace App\ValueObjects;

class Coordinates
{
    public function __construct(
        public readonly float $latitude,
        public readonly float $longitude
    ) {
        $this->validade();
    }

    private function validade(): void
    {
        if ($this->latitude < -90 || $this->latitude > 90) {
            throw new \InvalidArgumentException("Latitude must be between -90 and 90 degrees.");
        }
        if ($this->longitude < -180 || $this->longitude > 180) {
            throw new \InvalidArgumentException("Longitude must be between -180 and 180 degrees.");
        }
    }

    public function toArray(): array
    {
        return [
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ];
    }    
}