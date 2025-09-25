<?php

namespace App\Domain\Confirmation\ValueObjects;

use App\Domain\Common\ValueObjects\AbstractValueObject;

use App\Shared\Result;

final class TradeAction extends AbstractValueObject
{
    public const BUY = 'BUY';
    public const SELL = 'SELL';

    public static function tryFrom(string $value): Result
    {
        $allowed = [self::BUY, self::SELL];

        return (!in_array($value, $allowed, true))
            ? Result::failure("Invalid TradeAction: $value")
            : Result::success(new self($value));
    }

    public static function buy(): self
    {
        return new self(self::BUY);
    }

    public static function sell(): self
    {
        return new self(self::SELL);
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
