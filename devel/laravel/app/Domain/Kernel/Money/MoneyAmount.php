<?php

namespace App\Domain\Kernel\Money;

use App\Domain\Support\{
    ValueObjects\Abstract\AbstractStringValueObject,
};

use App\Foundation\Result;

final class MoneyAmount extends AbstractStringValueObject
{
    public static function tryFrom(string $value): Result
    {
        return parent::tryKind('MoneyAmount', $value);
    }

    public static function zero(): self
    {
        return parent::fromString('0.00');
    }

}
