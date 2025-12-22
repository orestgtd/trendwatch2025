<?php

namespace App\Domain\Kernel\Money;

interface MoneyCalculator
{
    public function add(Monetary $money1, Monetary $money2): Money;
    public function multiply(Monetary $money, int $quantity): Money;
    public function subtract(Monetary $money1, Monetary $money2): Money;
}
