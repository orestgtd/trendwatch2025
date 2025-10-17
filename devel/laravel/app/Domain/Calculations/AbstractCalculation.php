<?php

namespace App\Domain\Calculations;

use App\Domain\Services\DomainServices;

use App\Domain\Common\Money\MoneyCalculator;

abstract class AbstractCalculation
{
    protected static function calculator(): MoneyCalculator
    {
        return DomainServices::moneyCalculator();
    }
}
