<?php

namespace App\Infrastructure\Laravel\Eloquent\Trade\Dto;

use App\Domain\Security\ValueObjects\{
    SecurityNumber,
};

use App\Domain\Confirmation\ValueObjects\{
    PositionEffect,
    TradeAction,
    TradeNumber,
    TradeQuantity,
};

final class PersistedTradeDto
{
    public function __construct(
        public readonly SecurityNumber $securityNumber,
        public readonly TradeNumber $tradeNumber,
        public readonly TradeAction $tradeAction,
        public readonly PositionEffect $positionEffect,
        public readonly TradeQuantity $tradeQuantity,
    ) {}
}
