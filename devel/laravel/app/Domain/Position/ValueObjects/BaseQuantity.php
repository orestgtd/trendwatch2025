<?php

namespace App\Domain\Position\ValueObjects;

use App\Domain\{
    Position\ValueObjects\PositionQuantity,
    Support\ValueObjects\Abstract\AbstractIntValueObject,
};

use App\Foundation\Result;

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

    public static function zero(): self
    {
        return self::fromInt(0);
    }
}
