<?php

namespace App\Domain\Position\ValueObjects;

use App\Domain\{
    Common\ValueObjects\AbstractIntValueObject,
    Position\ValueObjects\PositionQuantity,
};

use App\Shared\Result;

final class BaseQuantity extends AbstractIntValueObject
{
    public static function tryFrom(int $value): Result
    {
        return $value >= 0
            ? Result::success(new static($value))
            : Result::failure("BaseQuantity must not be negative.");
    }

    public static function fromPositionQuantity(PositionQuantity $positionQuantity): self
    {
        return self::fromInt($positionQuantity->toInt());
    }
}
