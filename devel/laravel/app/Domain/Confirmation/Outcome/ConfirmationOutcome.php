<?php

namespace App\Domain\Confirmation\Outcome;

use App\Domain\{
    Outcome\Outcome,
    Outcome\Persistence\PersistenceIntent,
    Confirmation\Model\Confirmation,
};

interface ConfirmationOutcome extends Outcome
{
    public function getConfirmation(): Confirmation;
}
