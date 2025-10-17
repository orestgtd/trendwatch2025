<?php

namespace App\Domain\Calculations;

use App\Domain\Services\DomainServices;

use App\Domain\Confirmation\ValueObjects\CostAmount;

class NetCost
{
    public static function calculate(CostAmount $grossTransactionFees, CostAmount $totalBrokerFees): CostAmount
    {
        $calculator = DomainServices::moneyCalculator();

        $totalFees = $calculator->add($grossTransactionFees, $totalBrokerFees);

        return CostAmount::create(
            $totalFees->getAmount(),
            $totalFees->getCurrency()
        );

    }
}