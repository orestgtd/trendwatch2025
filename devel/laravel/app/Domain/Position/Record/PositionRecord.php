<?php

namespace App\Domain\Position\Record;

use App\Domain\Confirmation\{
    ValueObjects\CostAmount,
    ValueObjects\ProceedsAmount,
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

final class PositionRecord
{
    public function __construct(
        public readonly SecurityInfo $securityInfo,
        public readonly PositionType $positionType,
        public readonly PositionQuantity $positionQuantity,
        public readonly CostAmount $totalCost,
        public readonly ProceedsAmount $totalProceeds,
    ) {}
}
