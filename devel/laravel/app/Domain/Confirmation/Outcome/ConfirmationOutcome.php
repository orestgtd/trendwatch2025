<?php

namespace App\Domain\Confirmation\Outcome;

use App\Domain\{
    Outcome\Outcome,
    Confirmation\Model\Confirmation,
};

interface ConfirmationOutcome extends Outcome
{
    public function getConfirmation(): ?Confirmation;
}
