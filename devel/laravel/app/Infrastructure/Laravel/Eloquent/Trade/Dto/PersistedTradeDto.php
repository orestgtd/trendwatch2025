<?php

namespace App\Infrastructure\Laravel\Eloquent\Trade\Dto;

use App\Domain\Security\ValueObjects\{
    SecurityNumber,
};

use App\Domain\Confirmation\ValueObjects\{
    TradeNumber,
};

final class PersistedTradeDto
{
    public function __construct(
        public readonly SecurityNumber $securityNumber,
        public readonly TradeNumber $tradeNumber,
    ) {}
}
