<?php

namespace App\Domain\Confirmation\ValueObjects;

use App\Domain\Support\{
    ValueObjects\Abstract\AbstractIntValueObject,
};

use App\Shared\Result;

final class TradeQuantity extends AbstractIntValueObject
{
    public static function tryFrom(int $value): Result
    {
        return $value > 0
            ? Result::success(new static($value))
            : Result::failure("TradeQuantity must be greater than zero.");
    }
}
