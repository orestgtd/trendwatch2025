<?php

namespace App\Domain\Kernel\Identifiers;

use App\Domain\Support\{
    ValueObjects\Abstract\AbstractStringValueObject,
};

use App\Foundation\Result;

final class TradeNumber extends AbstractStringValueObject
{
    /** @return Result<self> */
    public static function tryFrom(string $value): Result
    {
        return parent::tryKind('TradeNumber', $value);
    }
}
