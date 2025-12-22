<?php

namespace App\Domain\Common\ValueObjects\Abstract;

use App\Shared\Result;

abstract class AbstractIntValueObject extends AbstractValueObject
{
    final protected function __construct(int $value)
    {
        parent::__construct($value);
    }

    public static function fromInt(int $value): static
    {
        return new static($value);
    }

    abstract public static function tryFrom(int $value): Result;

    public function toInt(): int
    {
        return $this->value;
    }
}
