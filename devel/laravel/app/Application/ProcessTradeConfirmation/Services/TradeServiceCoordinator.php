<?php

namespace App\Application\ProcessTradeConfirmation\Services;

use App\Application\ProcessTradeConfirmation\Services\{
    Parser\SecurityParser,
    Parser\TradeParser,
    PositionService,
    RegistrationService,
    SecurityService,
    TradeService,
};

final class TradeServiceCoordinator
{
    public function __construct(
        public readonly TradeService $tradeService,
        public readonly SecurityService $securityService,
        public readonly PositionService $positionService,
        public readonly RegistrationService $registrationService,
        public readonly TradeParser $tradeParser,
        public readonly SecurityParser $securityParser,
     ) {}
}
