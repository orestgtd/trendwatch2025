<?php

namespace App\Infrastructure\Laravel\Eloquent\Position\Dto;

use App\Domain\Kernel\{
    Identifiers\SecurityNumber,
    Values\PositionType,
    Values\UnitType,
};

use App\Domain\Position\ValueObjects\{
    PositionQuantity,
};

use App\Domain\Confirmation\ValueObjects\{
    CostAmount,
    ProceedsAmount,
};

final class PersistedPositionDto
{
    public function __construct(
        public readonly SecurityNumber $securityNumber,
        public readonly PositionType $positionType,
        public readonly PositionQuantity $positionQuantity,
        public readonly UnitType $unitType,
        public readonly CostAmount $totalCost,
        public readonly ProceedsAmount $totalProceeds,
    ) {}
}
