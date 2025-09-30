<?php

namespace App\Domain\Common\ValueObjects\Money;

use App\Shared\Result;

interface MoneyInterface
{
    // creation methods
    public static function tryFrom(?string $value, ?string $currency = 'USD'): Result;
    public static function zero(?string $currency = 'USD'): static;

    // getters
    public function getAmount(): string;
    public function getCurrency(): string;

    // arithmetic calculations
    // public static function add(MoneyInterface $money1, MoneyInterface $money2): MoneyInterface;
    // public static function subtract(MoneyInterface $money1, MoneyInterface $money2): MoneyInterface;
    // public static function multiply(MoneyInterface $amount, int $multiplier): MoneyInterface;

    // cast as string
    public function __toString(): string;
}
