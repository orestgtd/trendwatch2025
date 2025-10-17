<?php

namespace App\Domain\Calculations;

use App\Domain\Confirmation\ValueObjects\{
    Commission,
    CostAmount,
    UsTax,
};

use App\Domain\Services\DomainServices;

class TradeBrokerFees
{
    public static function calculate(Commission $commission, UsTax $usTax): CostAmount
    {
        $calculator = DomainServices::moneyCalculator();

        $totalFees = $calculator->add(
            $commission,
            $usTax
        );

        return CostAmount::create(
            $totalFees->getAmount(),
            $totalFees->getCurrency()
        );
    }
}
