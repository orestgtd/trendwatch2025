<?php

namespace App\Domain\Position\Builders;

use App\Domain\Confirmation\ValueObjects\{
    CostAmount,
    ProceedsAmount,
};

use App\Domain\Kernel\{
    Identifiers\SecurityNumber,
    Identifiers\Symbol,
    Values\ExpirationDate,
    Values\PositionType,
    Values\UnitType,
};

use App\Domain\Position\{
    Model\LongPosition,
    Model\Position,
    Model\ShortPosition,
    ValueObjects\PositionQuantity,
};

use App\Domain\Security\{
    ValueObjects\SecurityInfo,
};

final class BuildPositionFromPersisted
{
    public static function from(
        SecurityInfo $securityInfo,
        PositionType $positionType,
        PositionQuantity $positionQuantity,
        CostAmount $totalCost,
        ProceedsAmount $totalProceeds,
    ): Position {
        return $positionType->delegate(
            onLong: fn () => LongPosition::fromPersisted(
                $securityInfo, $positionQuantity,
                $totalCost, $totalProceeds
            ),
            onShort: fn () => ShortPosition::fromPersisted(
                $securityInfo, $positionQuantity,
                $totalCost, $totalProceeds
            )
        );
    }
}
