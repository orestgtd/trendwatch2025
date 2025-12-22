<?php

namespace App\Domain\Confirmation\Builders;

use App\Domain\Confirmation\Model\Confirmation;

use App\Domain\Confirmation\ValueObjects\{
    Commission,
    PositionEffect,
    TradeAction,
    TradeQuantity,
    TradeUnitType,
    UnitPrice,
    UsTax,
};

use App\Domain\Kernel\Identifiers\{
    SecurityNumber,
    TradeNumber,
};

final class BuildNewConfirmation
{
    public static function from(
        SecurityNumber $securityNumber,
        TradeNumber $tradeNumber,
        TradeAction $tradeAction,
        PositionEffect $positionEffect,
        TradeQuantity $tradeQuantity,
        TradeUnitType $tradeUnitType,
        UnitPrice $unitPrice,
        Commission $commission,
        UsTax $usTax,
    ): Confirmation {
        return Confirmation::create(
            $securityNumber,
            $tradeNumber,
            $tradeAction,
            $positionEffect,
            $tradeQuantity,
            $tradeUnitType,
            $unitPrice,
            $commission,
            $usTax,
        );
    }
}
