<?php

namespace App\Domain\Common\Money;

use App\Domain\Common\Money\{
    Currency,
    MoneyAmount,
};

use App\Shared\Result;

interface Monetary
{
    // creation methods
    // public static function tryFrom(MoneyAmount $amount, Currency $currency): Result;
    public static function zero(?Currency $currency): static;

    // getters
    public function getAmount(): MoneyAmount;
    public function getCurrency(): Currency;

    // arithmetic calculations
    // public static function add(Money $money1, Money $money2): Money;
    // public static function subtract(MoneyInterface $money1, MoneyInterface $money2): MoneyInterface;
    // public static function multiply(MoneyInterface $amount, int $multiplier): MoneyInterface;

    // cast as string
    // public function __toString(): string;
}
