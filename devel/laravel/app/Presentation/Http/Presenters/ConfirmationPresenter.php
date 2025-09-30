<?php

namespace App\Presentation\Http\Presenters;

use App\Domain\Confirmation\Outcome\ConfirmationOutcome;

final class ConfirmationPresenter
{
    public static function toArray(ConfirmationOutcome $outcome): array
    {
        $confirmation = $outcome->getConfirmation();

        return [
            'security_number' => (string) $confirmation->getSecurityNumber(),
            'trade_number' => (string) $confirmation->getTradeNumber(),
        ];
    }
}
