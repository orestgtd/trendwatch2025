<?php

namespace App\Domain\Security\Outcome;

use App\Domain\Security\Outcome\AbstractSecurityOutcome;

final class NoChange extends AbstractSecurityOutcome
{
    public function requiresPersistence(): bool
    {
        return false; // no changes, no persistence needed
    }
}
