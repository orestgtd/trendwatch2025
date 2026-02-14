<?php

namespace App\Presentation\Http\Presenters;

use App\Domain\{
    Confirmation\Outcome\ConfirmationOutcome,
    Position\Outcome\PositionOutcome,
    Security\Outcome\SecurityOutcome,
};

use App\Presentation\Http\Presenters\{
    ConfirmationPresenter,
    PositionPresenter,
    SecurityPresenter,
};

final class SummaryPresenter
{
    public function toArray(
        ConfirmationOutcome $confirmationOutcome,
        SecurityOutcome $securityOutcome,
        PositionOutcome $positionOutcome
        ): array
    {
        return [
            'confirmation' => ConfirmationPresenter::toArray($confirmationOutcome),
            'security' => SecurityPresenter::toArray($securityOutcome),
            'position' => PositionPresenter::toArray($positionOutcome),
        ];
    }
}
