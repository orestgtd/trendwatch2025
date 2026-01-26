<?php

namespace App\Infrastructure\Laravel\Eloquent\RealizedGain\Dto;

use App\Domain\Kernel\{
    Identifiers\SecurityNumber,
    Values\PositionType,
    Values\UnitType,
};

use App\Domain\Confirmation\ValueObjects\{
    CostAmount,
    ProceedsAmount,
    TradeQuantity,
};

use App\Domain\Position\ValueObjects\{
    BaseQuantity,
};

use App\Domain\RealizedGain\ValueObjects\{
    RealizationSource,
};

final class PersistedRealizedGainBasisDto
{
    public function __construct(
        public readonly SecurityNumber $securityNumber,
        public readonly PositionType $positionType,
        public readonly RealizationSource $realizationSource,
        public readonly BaseQuantity $baseQuantity,
        public readonly TradeQuantity $tradeQuantity,
        public readonly UnitType $unitType,
        public readonly CostAmount $cost,
        public readonly ProceedsAmount $proceeds,
    ) {}
}
