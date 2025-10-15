<?php

namespace App\Infrastructure\Laravel\Eloquent\Trade\Dto;

use App\Domain\Security\ValueObjects\{
    SecurityNumber,
};

use App\Domain\Confirmation\ValueObjects\{
    Commission,
    PositionEffect,
    TradeAction,
    TradeNumber,
    TradeQuantity,
    TradeUnitType,
    UnitPrice,
    UsTax,
};

final class PersistedTradeDto
{
    public function __construct(
        public readonly SecurityNumber $securityNumber,
        public readonly TradeNumber $tradeNumber,
        public readonly TradeAction $tradeAction,
        public readonly PositionEffect $positionEffect,
        public readonly TradeQuantity $tradeQuantity,
        public readonly TradeUnitType $tradeUnitType,
        public readonly UnitPrice $unitPrice,
        public readonly Commission $commission,
        public readonly UsTax $usTax,
    ) {}
}
