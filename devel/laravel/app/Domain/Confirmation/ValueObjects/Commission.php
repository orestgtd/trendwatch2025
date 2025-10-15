<?php

namespace App\Domain\Confirmation\ValueObjects;

use App\Domain\Common\Money\{
    AbstractMoney,
    Currency,
    MoneyAmount,
};

final class Commission extends AbstractMoney
{
    public static function create(MoneyAmount $amount, Currency $currency): static
    {
        return new self($amount, $currency);
    }
}
