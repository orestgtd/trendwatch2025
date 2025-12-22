<?php

namespace App\Domain\Confirmation\ValueObjects;

use App\Domain\Kernel\Money\{
    AbstractMoney,
    Currency,
    MoneyAmount,
};

final class ProceedsAmount extends AbstractMoney
{
    public static function create(MoneyAmount $amount, Currency $currency): static
    {
        return new self($amount, $currency);
    }
}