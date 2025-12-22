<?php

namespace App\Domain\Kernel\Money;

use App\Domain\Kernel\Money\{
    Currency,
    MoneyAmount,
};

interface Monetary
{
    // creation methods
    // public static function tryFrom(MoneyAmount $amount, Currency $currency): Result;
    public static function zero(?Currency $currency): static;

    // getters
    public function getAmount(): MoneyAmount;
    public function getCurrency(): Currency;

    // cast as string
    // public function __toString(): string;
}
