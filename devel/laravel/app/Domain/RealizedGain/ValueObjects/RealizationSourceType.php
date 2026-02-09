<?php

namespace App\Domain\RealizedGain\ValueObjects;

use App\Domain\Support\{
    ValueObjects\Abstract\AbstractStringValueObject,
};

use App\Foundation\Result;

final class RealizationSourceType extends AbstractStringValueObject
{
    public const TRADE = 'TRADE';
    public const EXPIRATION = 'EXPIRATION';

    public static function tryFrom(string $value): Result
    {
        $allowed = [self::TRADE, self::EXPIRATION];

        return (!in_array($value, $allowed, true))
            ? Result::failure("Invalid RealizationSourceType: $value")
            : Result::success(new self($value));
    }

    public static function trade(): self
    {
        return new self(self::TRADE);
    }

    public static function expiration(): self
    {
        return new self(self::EXPIRATION);
    }
}
