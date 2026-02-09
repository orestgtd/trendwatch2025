<?php

namespace App\Domain\Confirmation\ValueObjects;

use App\Domain\Support\{
    ValueObjects\Abstract\AbstractStringValueObject,
};

use App\Foundation\Result;

final class PositionEffect extends AbstractStringValueObject
{
    public const OPEN = 'OPEN';
    public const CLOSE = 'CLOSE';

    public static function tryFrom(string $value): Result
    {
        $allowed = [self::OPEN, self::CLOSE];

        return (!in_array($value, $allowed, true))
            ? Result::failure("Invalid PositionEffect: $value")
            : Result::success(new self($value));
    }

    public static function open(): self
    {
        return new self(self::OPEN);
    }

    public static function close(): self
    {
        return new self(self::CLOSE);
    }
}
