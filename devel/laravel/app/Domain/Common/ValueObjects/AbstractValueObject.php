<?php

namespace App\Domain\Common\ValueObjects;

use App\Shared\Result;

abstract class AbstractValueObject
{
    protected function __construct(
        protected readonly string $value
    ) {}

    public static function fromString(string $value)
    {
        return new static($value);
    }

    protected static function tryKind(string $kind, string $value): Result
    {
        $trimmed = trim($value);
        return ($trimmed === '')
            ? Result::failure('{$kind} cannot be empty.')
            : Result::success(new static($value));
    }

    abstract public static function tryFrom(string $value): Result;

    public function __toString(): string
    {
        return $this->value;
    }

    // public function equals(SecurityNumber $other): bool
    // {
    //     return $this->value === $other->value;
    // }
}
