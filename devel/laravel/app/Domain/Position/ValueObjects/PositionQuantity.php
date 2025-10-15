<?php

namespace App\Domain\Position\ValueObjects;

use App\Domain\{
    Common\ValueObjects\AbstractIntValueObject,
    Confirmation\ValueObjects\TradeQuantity,
};

use App\Shared\Result;

final class PositionQuantity extends AbstractIntValueObject
{
    public static function tryFrom(int $value): Result
    {
        return $value >= 0
            ? Result::success(new static($value))
            : Result::failure("PositionQuantity must not be negative.");
    }

    /** A PositionQuantity is initialized from the TradeQuantity for a new position */
    public static function fromTradeQuantity(TradeQuantity $tradeQuantity): self
    {
        return self::fromInt($tradeQuantity->toInt());
    }
}
