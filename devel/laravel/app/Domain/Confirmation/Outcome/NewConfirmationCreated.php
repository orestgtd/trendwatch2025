<?php

namespace App\Domain\Confirmation\Outcome;

use App\Domain\Confirmation\Model\Confirmation;

final class NewConfirmationCreated extends AbstractConfirmationOutcome
{
    public readonly Confirmation $confirmation;

    public function __construct(
        Confirmation $confirmation
    ) {
        $this->confirmation = $confirmation;
    }

    public function requiresPersistence(): bool
    {
        return true; // incoming confirmation must be persisted
    }
}
