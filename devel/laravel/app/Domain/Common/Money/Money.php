<?php

namespace App\Domain\Common\Money;

use App\Domain\Common\Money\{
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