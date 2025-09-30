<?php

namespace App\Domain\Security\Outcome;

use App\Domain\Security\{
    Outcome\AbstractSecurityOutcome,
};

final class NewSecurityCreated extends AbstractSecurityOutcome
{
    public function requiresPersistence(): bool
    {
        return true; // new security must be persisted
    }
}
