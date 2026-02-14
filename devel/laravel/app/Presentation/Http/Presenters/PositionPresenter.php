<?php

namespace App\Presentation\Http\Presenters;

use App\Domain\{
    Position\Outcome\PositionOutcome,
};

use Illuminate\Http\JsonResponse;

final class PositionPresenter
{
    public static function toArray(PositionOutcome $outcome): array
    {
        $position = $outcome->getPosition();

        return [
            'security_number' => (string) $position->getSecurityNumber(),
            'symbol' => (string) $position->getSymbol(),
            'description' => (string) $position->getDescription(),
            'unit_type' => (string) $position->getUnitType(),
        ];
    }

    public static function toJson(PositionOutcome $outcome): JsonResponse
    {
        return response()->json(
            self::toArray($outcome)
        );
    }
}
