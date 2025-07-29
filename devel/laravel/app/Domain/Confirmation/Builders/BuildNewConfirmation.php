<?php

namespace App\Domain\Confirmation\Builders;

use App\Domain\Confirmation\{
    Model\Confirmation,
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
    ): Confirmation {
        return Confirmation::create($securityNumber, $tradeNumber);
    }
}
