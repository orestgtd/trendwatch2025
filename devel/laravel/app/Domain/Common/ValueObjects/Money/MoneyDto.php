<?php

namespace App\Domain\Common\ValueObjects\Money;

use App\Shared\Result;

final class MoneyDto
{
    private function __construct(
        public readonly string $amount,
        public readonly string $currency
    ) {}

    public static function create(string $amount, ?string $currency = 'USD'): self
    {
        return new self($amount, $currency);
    }

    public static function tryFrom(string $input, ?string $currency = 'USD'): Result
    {
        // Here we chould add lightweight guards:
        // - regex for decimal format
        // - ensure currency is ISO 4217

        return Result::success(new self ($input, $currency));
    }

    public static function zero(?string $currency = 'USD'): static
    {
        return new static('0.00', $currency);
    }

    public function __toString(): string
    {
        return $this->amount;
    }
}
