<?php

namespace App\Domain\Calculations;

use App\Domain\Services\DomainServices;

use App\Domain\Confirmation\ValueObjects\ProceedsAmount;

class AddProceeds
{
    public static function calculate(ProceedsAmount $proceeds1, ProceedsAmount $proceeds2): ProceedsAmount
    {
        $calculator = DomainServices::moneyCalculator();

        $totalProceeds = $calculator->add($proceeds1, $proceeds2);

        return ProceedsAmount::create(
            $totalProceeds->getAmount(),
            $totalProceeds->getCurrency()
        );

    }
}