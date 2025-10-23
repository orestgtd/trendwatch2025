<?php

namespace App\Domain\Calculations;

use App\Domain\Services\DomainServices;

use App\Domain\Confirmation\ValueObjects\CostAmount;

class AddCost
{
    public static function calculate(CostAmount $cost1, CostAmount $cost2): CostAmount
    {
        $calculator = DomainServices::moneyCalculator();

        $totalCost = $calculator->add($cost1, $cost2);

        return CostAmount::create(
            $totalCost->getAmount(),
            $totalCost->getCurrency()
        );

    }
}