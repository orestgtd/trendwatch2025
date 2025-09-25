<?php

namespace App\Domain\Confirmation\Builders;

use App\Domain\Confirmation\{
    Model\Confirmation,
    ValueObjects\PositionEffect,
    ValueObjects\TradeAction,
    ValueObjects\TradeNumber,
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
    ): Confirmation {
        return Confirmation::create(
            $securityNumber, $tradeNumber, $tradeAction, $positionEffect
        );
    }
}
