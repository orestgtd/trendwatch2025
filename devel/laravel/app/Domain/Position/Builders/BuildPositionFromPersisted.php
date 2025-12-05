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
        return $positionType->delegate(
            onLong: fn () => LongPosition::fromPersisted(
                $securityNumber, $positionQuantity,
                $totalCost, $totalProceeds
            ),
            onShort: fn () => ShortPosition::fromPersisted(
                $securityNumber, $positionQuantity,
                $totalCost, $totalProceeds
            )
        );
    }
}
