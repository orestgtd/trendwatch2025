<?php

namespace App\Domain\Confirmation\ValueObjects;

use App\Domain\Common\ValueObjects\AbstractValueObject;

use App\Shared\Result;

final class PositionEffect extends AbstractValueObject
{
    public const OPEN = 'OPEN';
    public const CLOSE = 'CLOSE';

    public static function tryFrom(string $value): Result
    {
        $allowed = [self::OPEN, self::CLOSE];

        return (!in_array($value, $allowed, true))
            ? Result::failure("Invalid PositionEffect: $value")
            : Result::success(new self($value));
    }

    public static function open(): self
    {
        return new self(self::OPEN);
    }

    public static function close(): self
    {
        return new self(self::CLOSE);
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
