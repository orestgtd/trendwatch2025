<?php

namespace App\Domain\Kernel\Money;

use App\Domain\Kernel\Money\{
    AbstractMoney,
    Currency,
    MoneyAmount,
};

final class Money extends AbstractMoney
{
    public static function create(MoneyAmount $amount, Currency $currency): static
    {
        return new self($amount, $currency);
    }
}