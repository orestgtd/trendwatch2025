<?php

namespace App\Domain\Confirmation\Builders;

use App\Domain\Confirmation\Model\Confirmation;

use App\Domain\Confirmation\ValueObjects\{
    PositionEffect,
    TradeAction,
    TradeNumber,
    TradeQuantity,
    UnitPrice,
    Commission,
    UsTax,
};

use App\Domain\Security\{
    ValueObjects\SecurityNumber,
};

final class BuildNewConfirmation
{
    public static function from(
        SecurityNumber $securityNumber,
        TradeNumber $tradeNumber,
        TradeAction $tradeAction,
        PositionEffect $positionEffect,
        TradeQuantity $tradeQuantity,
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
            $unitPrice,
            $commission,
            $usTax,
        );
    }
}
