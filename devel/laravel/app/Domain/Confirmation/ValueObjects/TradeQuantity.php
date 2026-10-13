<?php

namespace App\Domain\Confirmation\ValueObjects;

use App\Domain\Support\{
    ValueObjects\Abstract\AbstractIntValueObject,
};

use App\Foundation\Result;

final class TradeQuantity extends AbstractIntValueObject
{
    /** @return Result<self> */
    public static function tryFrom(int $value): Result
    {
        return $value > 0
            ? Result::success(new static($value))
            : Result::failure("TradeQuantity must be greater than zero.");
    }
}
