<?php

namespace App\Infrastructure\Laravel\Eloquent\Position\Dto;

use App\Domain\Confirmation\ValueObjects\{
    CostAmount,
    ProceedsAmount,
};

use App\Domain\Kernel\{
    Identifiers\SecurityNumber,
    Identifiers\Symbol,
    Values\PositionType,
    Values\UnitType,
};

use App\Domain\Position\ValueObjects\{
    PositionQuantity,
};

use App\Domain\Security\{
    ValueObjects\ExpirationDate\ExpirationDateInterface,
};

final class PersistedPositionDto
{
    public function __construct(
        public readonly SecurityNumber $securityNumber,
        public readonly Symbol $symbol,
        public readonly PositionType $positionType,
        public readonly PositionQuantity $positionQuantity,
        public readonly UnitType $unitType,
        public readonly CostAmount $totalCost,
        public readonly ProceedsAmount $totalProceeds,
        public readonly ExpirationDateInterface $expirationDate,
    ) {}
}
