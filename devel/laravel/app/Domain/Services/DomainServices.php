<?php

namespace App\Domain\Services;

use App\Domain\Kernel\Money\MoneyCalculator;

final class DomainServices
{
    private static ?MoneyCalculator $moneyCalculator = null;

    public static function setMoneyCalculator(MoneyCalculator $calculator): void
    {
        self::$moneyCalculator = $calculator;
    }

    public static function moneyCalculator(): MoneyCalculator
    {
        if (!self::$moneyCalculator) {
            throw new \RuntimeException('MoneyCalculator has not been configured.');
        }

        return self::$moneyCalculator;
    }
}
