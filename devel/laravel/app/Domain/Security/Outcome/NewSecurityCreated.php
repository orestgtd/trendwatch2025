<?php

namespace App\Domain\Security\Outcome;

final class NewSecurityCreated implements SecurityOutcome
{
    public function requiresPersistence(): bool
    {
        return true; // new security must be persisted
    }
}
