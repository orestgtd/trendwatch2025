<?php

namespace App\Infrastructure\Laravel\Eloquent\Trade\Dto;

use App\Domain\Kernel\{
    Identifiers\SecurityNumber,
    Identifiers\Symbol,
    Identifiers\TradeNumber,
    Values\ExpirationDate,
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
    ValueObjects\SecurityInfo,
};

final class PersistedTradeDto
{
    public function __construct(
        public readonly SecurityInfo $securityInfo,
        public readonly TradeNumber $tradeNumber,
        public readonly TradeAction $tradeAction,
        public readonly PositionEffect $positionEffect,
        public readonly TradeQuantity $tradeQuantity,
        public readonly UnitPrice $unitPrice,
        public readonly Commission $commission,
        public readonly UsTax $usTax,
    ) {}
}
