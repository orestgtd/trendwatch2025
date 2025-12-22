<?php

namespace App\Domain\Position\Builders;

use App\Domain\Kernel\{
    Identifiers\SecurityNumber,
    Values\PositionType,
    Values\UnitType,
};

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
};

final class BuildPositionFromPersisted
{
    public static function from(
        SecurityNumber $securityNumber,
        PositionType $positionType,
        PositionQuantity $positionQuantity,
        UnitType $unitType,
        CostAmount $totalCost,
        ProceedsAmount $totalProceeds,
    ): Position {
        return $positionType->delegate(
            onLong: fn () => LongPosition::fromPersisted(
                $securityNumber, $positionQuantity, $unitType,
                $totalCost, $totalProceeds
            ),
            onShort: fn () => ShortPosition::fromPersisted(
                $securityNumber, $positionQuantity, $unitType,
                $totalCost, $totalProceeds
            )
        );
    }
}
