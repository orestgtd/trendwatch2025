<?php

namespace App\Presentation\Http\Presenters;

use App\Domain\Security\Outcome\SecurityOutcome;

use Illuminate\Http\JsonResponse;

final class SecurityPresenter
{
    public static function toArray(SecurityOutcome $outcome): array
    {
        $security = $outcome->getSecurity();

        return [
            'security_number' => (string) $security->getSecurityNumber(),
            'symbol' => (string) $security->getSymbol(),
            'description' => (string) $security->getCanonicalDescription(),
            'unit_type' => (string) $security->getUnitType(),
        ];
    }

    public static function toJson(SecurityOutcome $outcome): JsonResponse
    {
        return response()->json(
            self::toArray($outcome)
        );
    }
}
