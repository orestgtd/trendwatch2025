<?php

namespace App\Domain\Support\ValueObjects\Abstract;

use App\Foundation\Result;

abstract class AbstractIntValueObject extends AbstractValueObject
{
    abstract public static function tryFrom(int $value): Result;

    final protected function __construct(int $value)
    {
        parent::__construct($value);
    }

    public static function fromInt(int $value): static
    {
        return new static($value);
    }

    public function toInt(): int
    {
        return $this->value;
    }

    public static function zero(): static
    {
        return new static(0);
    }
}
