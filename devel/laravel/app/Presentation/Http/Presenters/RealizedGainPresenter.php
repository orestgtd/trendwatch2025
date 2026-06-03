<?php

namespace App\Presentation\Http\Presenters;

use App\Domain\{
    RealizedGain\Record\RealizedGainBasisRecord,
};

final class RealizedGainPresenter
{
    /** @return array<string, string> */
    public static function toArray(
        RealizedGainBasisRecord $record
    ): array {
        return [
            'security_number' => (string) $record->securityNumber,
            'realization_date' => (string) $record->realizationDate,
            'position_type' => (string) $record->positionType,
            'realization_source' => (string) $record->realizationSource,
            'base_quantity' => (string) $record->baseQuantity,
            'trade_quantity' => (string) $record->tradeQuantity,
            'unit_type' => (string) $record->unitType,
            'cost' => (string) $record->cost,
            'proceeds' => (string) $record->proceeds,
        ];
    }
}
