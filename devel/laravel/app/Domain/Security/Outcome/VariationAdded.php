<?php

namespace App\Domain\Security\Outcome;

use App\Domain\Security\Outcome\AbstractSecurityOutcome;

final class VariationAdded extends AbstractSecurityOutcome
{
    public function requiresPersistence(): bool
    {
        return true; // new variations always require persistence
    }
}