<?php

namespace App\Domain\Security\Outcome;

use App\Domain\Security\Outcome\AbstractOutcome;

final class NewSecurityCreated extends AbstractOutcome
{
    public function requiresPersistence(): bool
    {
        return true; // new security must be persisted
    }
}
