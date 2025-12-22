<?php

namespace App\Domain\Kernel\Values;

use App\Domain\Support\{
    ValueObjects\Abstract\AbstractStringValueObject,
};

use App\Shared\Result;

final class UnitType extends AbstractStringValueObject
{
    public const SHARES = 'SHARES';
    public const CONTRACTS = 'CONTRACTS';

    public static function tryFrom(string $value): Result
    {
        $allowed = [self::SHARES, self::CONTRACTS];

        return (!in_array($value, $allowed, true))
            ? Result::failure("Invalid UnitType: $value")
            : Result::success(new self($value));
    }

    public static function shares(): self
    {
        return new self(self::SHARES);
    }

    public static function contracts(): self
    {
        return new self(self::CONTRACTS);
    }

    public function multiplier(): int
    {
        return match($this->value) {
            self::SHARES => 1,
            self::CONTRACTS => 100,
        };
    }

    /**
     * @template T
     * @param callable():T $onShares
     * @param callable():T $onContracts
     * @return T
     */
    public function delegate(callable $onShares, callable $onContracts)
    {
        return match ($this->value()) {
            self::SHARES => $onShares(),
            self::CONTRACTS => $onContracts(),
            default => throw new \LogicException("Unhandled UnitType: {$this->value()}"),
        };
    }
}
