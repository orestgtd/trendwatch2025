<?php

namespace App\Presentation\Http\Presenters;

use App\Domain\Outcome\Outcome;
use App\Domain\Outcome\OutcomeKind;

use Illuminate\Http\JsonResponse;

final class OutcomePresenter
{
    public static function toArray(Outcome $outcome): array
    {
        $kind = $outcome->kind();

        return match($kind) {
            OutcomeKind::CONFIRMATION => ConfirmationPresenter::toArray($outcome),
            OutcomeKind::SECURITY => SecurityPresenter::toArray($outcome),
        };
    }

    public static function toJson(Outcome $outcome): JsonResponse
    {
        return response()->json(
            self::toArray($outcome)
        );
    }
}
