<?php

namespace App\Domain\Common\Money;

use App\Domain\Common\ValueObjects\AbstractStringValueObject;

use App\Shared\Result;

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
