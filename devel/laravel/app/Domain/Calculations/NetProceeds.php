<?php

namespace App\Domain\Calculations;

use App\Domain\Services\DomainServices;

use App\Domain\Confirmation\ValueObjects\{
    CostAmount,
    ProceedsAmount,
};

class NetProceeds
{
    public static function calculate(ProceedsAmount $grossTransactionFees, CostAmount $totalBrokerFees): ProceedsAmount
    {
        $calculator = DomainServices::moneyCalculator();

        $totalFees = $calculator->subtract($grossTransactionFees, $totalBrokerFees);

        return ProceedsAmount::create(
            $totalFees->getAmount(),
            $totalFees->getCurrency()
        );
    }
}
