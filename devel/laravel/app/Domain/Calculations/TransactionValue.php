<?php

namespace App\Domain\Calculations;

use App\Domain\Confirmation\ValueObjects\{
    TradeQuantity,
    TransactionAmount,
    UnitPrice,
};

use App\Domain\Security\ValueObjects\{
    UnitType,
};

final class TransactionValue extends AbstractCalculation
{
    public static function calculate(TradeQuantity $quantity, UnitType $unitType, UnitPrice $price): TransactionAmount
    {
        return TransactionAmount::fromMoney(
            self::calculator()->multiply(
                $price,
                $quantity->value() * $unitType->multiplier()
            )
        );
    }
}
