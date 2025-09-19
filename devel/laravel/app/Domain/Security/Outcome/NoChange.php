<?php

namespace App\Domain\Security\Outcome;

use App\Domain\Security\Outcome\AbstractOutcome;

final class NoChange extends AbstractOutcome
{
    public function requiresPersistence(): bool
    {
        return false; // no changes, no persistence needed
    }
}
