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
    Identifiers\SecurityNumber,
    Identifiers\Symbol,
    Identifiers\TradeNumber,
    Values\UnitType,
};

use App\Domain\Security\{
    ValueObjects\ExpirationDate\ExpirationDate,
};

final class BuildNewConfirmation
{
    public static function from(
        SecurityNumber $securityNumber,
        Symbol $symbol,
        TradeNumber $tradeNumber,
        TradeAction $tradeAction,
        PositionEffect $positionEffect,
        TradeQuantity $tradeQuantity,
        UnitType $unitType,
        UnitPrice $unitPrice,
        Commission $commission,
        UsTax $usTax,
        ExpirationDate $expirationDate,
    ): Confirmation {
        return Confirmation::create(
            $securityNumber,
            $symbol,
            $tradeNumber,
            $tradeAction,
            $positionEffect,
            $tradeQuantity,
            $unitType,
            $unitPrice,
            $commission,
            $usTax,
            $expirationDate,
        );
    }
}
