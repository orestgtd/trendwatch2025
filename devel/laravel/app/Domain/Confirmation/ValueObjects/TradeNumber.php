<?php

namespace App\Domain\Confirmation\ValueObjects;

use App\Domain\Common\ValueObjects\AbstractStringValueObject;

use App\Shared\Result;

final class TradeNumber extends AbstractStringValueObject
{
    public static function tryFrom(string $value): Result
    {
        return parent::tryKind('TradeNumber', $value);
    }
}
