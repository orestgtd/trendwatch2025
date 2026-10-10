<?php

namespace App\Presentation\Http\Presenters;

use App\Domain\{
    Confirmation\Outcome\ConfirmationOutcome,
    Outcome\Outcome,
    Security\Outcome\SecurityOutcome
};
use Illuminate\Http\JsonResponse;

final class OutcomePresenter
{
    public static function toArray(Outcome $outcome): array
    {
        return match(true) {
            $outcome instanceof ConfirmationOutcome => ConfirmationPresenter::toArray($outcome),
            $outcome instanceof SecurityOutcome => SecurityPresenter::toArray($outcome),
            default => [],
        };
    }

    public static function toJson(Outcome $outcome): JsonResponse
    {
        return response()->json(
            self::toArray($outcome)
        );
    }
}
