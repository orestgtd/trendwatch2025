<?php

namespace App\Infrastructure\Laravel\Eloquent\Trade\Dto;

use App\Domain\Kernel\{
    Identifiers\SecurityNumber,
    Identifiers\Symbol,
    Identifiers\TradeNumber,
    Values\UnitType,
};

use App\Domain\Confirmation\ValueObjects\{
    Commission,
    PositionEffect,
    TradeAction,
    TradeQuantity,
    UnitPrice,
    UsTax,
};

use App\Domain\Security\{
    ValueObjects\ExpirationDate\ExpirationDate,
};

final class PersistedTradeDto
{
    public function __construct(
        public readonly SecurityNumber $securityNumber,
        public readonly Symbol $symbol,
        public readonly TradeNumber $tradeNumber,
        public readonly TradeAction $tradeAction,
        public readonly PositionEffect $positionEffect,
        public readonly TradeQuantity $tradeQuantity,
        public readonly UnitType $unitType,
        public readonly UnitPrice $unitPrice,
        public readonly Commission $commission,
        public readonly UsTax $usTax,
        public readonly ExpirationDate $expirationDate,
    ) {}
}
