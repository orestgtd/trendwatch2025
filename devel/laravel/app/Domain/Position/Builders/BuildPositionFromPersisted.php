<?php

namespace App\Domain\Position\Builders;

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

use App\Domain\Position\{
    Model\LongPosition,
    Model\Position,
    Model\ShortPosition,
    ValueObjects\PositionQuantity,
};

final class BuildPositionFromPersisted
{
    public static function from(
        SecurityNumber $securityNumber,
        Symbol $symbol,
        PositionType $positionType,
        PositionQuantity $positionQuantity,
        UnitType $unitType,
        CostAmount $totalCost,
        ProceedsAmount $totalProceeds,
    ): Position {
        return $positionType->delegate(
            onLong: fn () => LongPosition::fromPersisted(
                $securityNumber, $symbol, $positionQuantity, $unitType,
                $totalCost, $totalProceeds
            ),
            onShort: fn () => ShortPosition::fromPersisted(
                $securityNumber, $symbol, $positionQuantity, $unitType,
                $totalCost, $totalProceeds
            )
        );
    }
}
