<?php

namespace App\Domain\Security\Outcome;

use App\Domain\Security\Outcome\AbstractOutcome;

final class VariationAdded extends AbstractOutcome
{
    public function requiresPersistence(): bool
    {
        return true; // new variations always require persistence
    }
}