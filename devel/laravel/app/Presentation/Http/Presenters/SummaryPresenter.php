<?php

namespace App\Presentation\Http\Presenters;

use App\Domain\{
    Confirmation\Outcome\ConfirmationOutcome,
    Security\Outcome\SecurityOutcome,
};

use App\Presentation\Http\Presenters\{
    ConfirmationPresenter,
    SecurityPresenter,
};

final class SummaryPresenter
{
    public function toArray(
        ConfirmationOutcome $confirmationOutcome,
        SecurityOutcome $securityOutcome
        ): array
    {
        return [
            'confirmation' => ConfirmationPresenter::toArray($confirmationOutcome),
            'security' => SecurityPresenter::toArray($securityOutcome),
        ];
    }
}
