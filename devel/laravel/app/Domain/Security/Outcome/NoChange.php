<?php

namespace App\Domain\Security\Outcome;

final class NoChange implements SecurityOutcome
{
    public function requiresPersistence(): bool
    {
        return false; // no changes, no persistence needed
    }
}
