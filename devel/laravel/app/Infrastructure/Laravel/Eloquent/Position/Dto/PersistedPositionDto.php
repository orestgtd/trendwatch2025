<?php

namespace App\Infrastructure\Laravel\Eloquent\Position\Dto;

use App\Domain\Confirmation\ValueObjects\{
    CostAmount,
    ProceedsAmount,
};

use App\Domain\Kernel\{
    Values\PositionType,
};

use App\Domain\Position\{
    ValueObjects\PositionQuantity,
};

use App\Domain\Security\{
    ValueObjects\SecurityInfo,
};

final class PersistedPositionDto
{
    public function __construct(
        public readonly SecurityInfo $securityInfo,
        public readonly PositionType $positionType,
        public readonly PositionQuantity $positionQuantity,
        public readonly CostAmount $totalCost,
        public readonly ProceedsAmount $totalProceeds,
    ) {}
}
