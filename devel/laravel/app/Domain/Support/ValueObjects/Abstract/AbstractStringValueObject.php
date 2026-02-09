<?php

namespace App\Domain\Support\ValueObjects\Abstract;

use App\Foundation\Result;

abstract class AbstractStringValueObject extends AbstractValueObject
{
    final protected function __construct(string $value)
    {
        parent::__construct($value);
    }

    public static function fromString(string $value): static
    {
        return new static($value);
    }

    protected static function tryKind(string $kind, string $value): Result
    {
        $trimmed = trim($value);

        return $trimmed === ''
            ? Result::failure("{$kind} cannot be empty.")
            : Result::success(new static($trimmed));
    }

    abstract public static function tryFrom(string $value): Result;
}
