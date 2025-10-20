<?php

namespace App\Domain\Position\Builders;

use App\Domain\Position\Model\{
    LongPosition,
    Position,
    ShortPosition,
};

use App\Domain\Confirmation\ValueObjects\{
    CostAmount,
    ProceedsAmount,
};

use App\Domain\Position\ValueObjects\{
    PositionQuantity,
    PositionType,
};

use App\Domain\Security\ValueObjects\{
    SecurityNumber,
};

final class BuildPositionFromPersisted
{
    public static function from(
        SecurityNumber $securityNumber,
        PositionType $positionType,
        PositionQuantity $positionQuantity,
        CostAmount $totalCost,
        ProceedsAmount $totalProceeds,
    ): Position {
        return match ($positionType->value()) {
            PositionType::LONG => LongPosition::create(
                $securityNumber, $positionQuantity,
                $totalCost, $totalProceeds
            ),
            PositionType::SHORT => ShortPosition::create(
                $securityNumber, $positionQuantity,
                $totalCost, $totalProceeds
            ),
        };
    }
}
