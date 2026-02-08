<?php

namespace App\Domain\Confirmation\Builders;

use App\Domain\Confirmation\Model\Confirmation;

use App\Domain\Confirmation\ValueObjects\{
    Commission,
    PositionEffect,
    TradeAction,
    TradeQuantity,
    UnitPrice,
    UsTax,
};

use App\Domain\Kernel\{
    Identifiers\TradeNumber,
};

use App\Domain\Security\{
    ValueObjects\SecurityInfo,
};

final class BuildNewConfirmation
{
    public static function from(
        SecurityInfo $securityInfo,
        TradeNumber $tradeNumber,
        TradeAction $tradeAction,
        PositionEffect $positionEffect,
        TradeQuantity $tradeQuantity,
        UnitPrice $unitPrice,
        Commission $commission,
        UsTax $usTax,
    ): Confirmation {
        return Confirmation::create(
            $securityInfo,
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
