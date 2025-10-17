<?php

namespace App\Domain\Common\Money;

use App\Domain\Common\Money\Monetary;

interface MoneyCalculator
{
    public function add(Monetary $money1, Monetary $money2): Monetary;
    public function multiply(Monetary $money, int $quantity): Monetary;
    public function subtract(Monetary $money1, Monetary $money2): Monetary;
}
