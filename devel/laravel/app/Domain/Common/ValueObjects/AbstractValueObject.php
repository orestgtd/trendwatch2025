<?php

namespace App\Domain\Common\ValueObjects;

abstract class AbstractValueObject
{
    protected function __construct(
        protected readonly mixed $value
    ) {}

    public function value(): mixed
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
