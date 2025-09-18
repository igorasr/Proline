<?php

namespace App\ValueObjects;

class Monetary
{
  public function __construct(private float $value)
  {
    $this->validade();
  }

  private function validade()
  {
    if ($this->value < 0) {
      throw new \InvalidArgumentException('Monetary value cannot be negative.');
    }

    return true;
  }

  public function value(): float
  {
    return $this->value;
  }

  public function add(Monetary $other): self
  {
    $this->value += $other->value();

    return $this;
  }

  public function subtract(Monetary $other): self
  {
    if ($this->lessThan($other)) {
      throw new \InvalidArgumentException('Insufficient value to subtract.');
    }

    $this->value -= $other->value();
    return $this;
  }

  public static function ZERO(): Monetary
  {
    return new Monetary(0.00);
  }

  public function equals(Monetary $other): bool
  {
    return $this->value === $other->value();
  }

  public function lessThan(Monetary $other): bool
  {
    return $this->value < $other->value();
  }
}