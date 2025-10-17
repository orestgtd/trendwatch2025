<?php

namespace App\Domain\Calculations;

use App\Domain\Confirmation\ValueObjects\{
    CostAmount,
    TradeQuantity,
    UnitPrice,
};

use App\Domain\Security\ValueObjects\{
    UnitType,
};

final class GrossTransactionAmount extends AbstractCalculation
{
    public static function calculate(TradeQuantity $quantity, UnitType $unitType, UnitPrice $price): CostAmount
    {
        return CostAmount::fromMoney(
            self::calculator()->multiply(
                $price,
                $quantity->value() * $unitType->multiplier()
            )
        );
    }
}
