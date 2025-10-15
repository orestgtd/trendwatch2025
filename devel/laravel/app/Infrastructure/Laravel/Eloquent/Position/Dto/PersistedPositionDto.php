<?php

namespace App\Infrastructure\Laravel\Eloquent\Position\Dto;

use App\Domain\Position\ValueObjects\{
    PositionQuantity,
    PositionType,
};

use App\Domain\Confirmation\ValueObjects\{
    CostAmount,
    ProceedsAmount,
};

use App\Domain\Security\ValueObjects\{
    SecurityNumber,
};

final class PersistedPositionDto
{
    public function __construct(
        public readonly SecurityNumber $securityNumber,
        public readonly PositionType $positionType,
        public readonly PositionQuantity $positionQuantity,
        public readonly CostAmount $totalCost,
        public readonly ProceedsAmount $totalProceeds,
    ) {}
}
