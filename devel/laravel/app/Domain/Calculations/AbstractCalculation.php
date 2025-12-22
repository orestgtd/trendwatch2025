<?php

namespace App\Domain\Calculations;

use App\Domain\{
    Kernel\Money\MoneyCalculator,
    Services\DomainServices,
};

abstract class AbstractCalculation
{
    protected static function calculator(): MoneyCalculator
    {
        return DomainServices::moneyCalculator();
    }
}
