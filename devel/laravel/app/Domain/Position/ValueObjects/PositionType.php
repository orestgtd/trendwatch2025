<?php

namespace App\Domain\Position\ValueObjects;

use App\Domain\Common\ValueObjects\AbstractStringValueObject;

use App\Shared\Result;

final class PositionType extends AbstractStringValueObject
{
    public const LONG = 'LONG';
    public const SHORT = 'SHORT';

    public static function tryFrom(string $value): Result
    {
        $allowed = [self::LONG, self::SHORT];

        return (!in_array($value, $allowed, true))
            ? Result::failure("Invalid PositionType: $value")
            : Result::success(new self($value));
    }

    public static function long(): self
    {
        return new self(self::LONG);
    }

    public static function short(): self
    {
        return new self(self::SHORT);
    }
}
