<?php

namespace App\Presentation\Http\Presenters;

use App\Domain\Security\Outcome\SecurityOutcome;

use Illuminate\Http\JsonResponse;

final class SecurityPresenter
{
    public static function toArray(SecurityOutcome $outcome): array
    {
        $security = $outcome->security;

        return [
            'security_number' => (string) $security->securityNumber(),
            'symbol' => (string) $security->symbol(),
            'description' => (string) $security->canonicalDescription(),
            'unit_type' => (string) $security->unitType(),
        ];
    }

    public static function toJson(SecurityOutcome $outcome): JsonResponse
    {
        return response()->json(
            self::toArray($outcome)
        );
    }
}
