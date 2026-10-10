<?php

namespace App\Domain\Kernel\Values;

use App\Domain\Support\{
    ValueObjects\Abstract\AbstractStringValueObject,
};

use App\Foundation\Result;

final class PositionType extends AbstractStringValueObject
{
    public const LONG = 'LONG';
    public const SHORT = 'SHORT';

    public static function tryFrom(string $value): Result
    {
        $allowed = [self::LONG, self::SHORT];

        return (!in_array($value, $allowed, true))
            ? Result::failure("Invalid PositionType: $value")
            : Result::success(new self($value));
    }

    public static function long(): self
    {
        return new self(self::LONG);
    }

    public static function short(): self
    {
        return new self(self::SHORT);
    }

    public function isLong(): bool
    {
        return $this->value == self::LONG;
    }

    public function isShort(): bool
    {
        return $this->value == self::SHORT;
    }

    /**
     * @template T
     * @template TLongArgs
     * @template TShortArgs
     *
     * @param callable(TLongArgs):T $onLong
     * @param callable(TShortArgs):T $onShort
     * @param mixed ...$args
     *
     * @return T
     */
    public function delegate(callable $onLong, callable $onShort, ...$args)
    {
        return match ($this->value()) {
            self::LONG => $onLong(...$args),
            self::SHORT => $onShort(...$args),
            default => throw new \LogicException("Unhandled PositionType: {$this->value()}"),
        };
    }
}
