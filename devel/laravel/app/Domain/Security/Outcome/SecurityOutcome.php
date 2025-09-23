<?php

namespace App\Domain\Security\Outcome;

interface SecurityOutcome
{
    public function requiresPersistence(): bool;
}
