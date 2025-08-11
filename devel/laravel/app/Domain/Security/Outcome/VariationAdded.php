<?php

namespace App\Domain\Security\Outcome;

final class VariationAdded implements SecurityOutcome
{
    public function requiresPersistence(): bool
    {
        return true; // new variations always require persistence
    }
}