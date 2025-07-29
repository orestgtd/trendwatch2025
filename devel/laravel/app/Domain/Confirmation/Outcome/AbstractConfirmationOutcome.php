<?php

namespace App\Domain\Confirmation\Outcome;

use App\Domain\Outcome\OutcomeKind;

abstract class AbstractConfirmationOutcome implements ConfirmationOutcome
{
    public function kind(): OutcomeKind
    {
        return OutcomeKind::CONFIRMATION;
    }
}
