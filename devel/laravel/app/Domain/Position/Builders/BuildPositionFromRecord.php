<?php

namespace App\Domain\Position\Builders;

use App\Domain\Position\{
    Model\LongPosition,
    Model\Position,
    Model\ShortPosition,
    Record\PositionRecord,
};

final class BuildPositionFromRecord
{
    public static function from(PositionRecord $record): Position
    {
        return $record->positionType->delegate(
            onLong: fn () => LongPosition::fromRecord($record),
            onShort: fn () => ShortPosition::fromRecord($record),
        );
    }
}
