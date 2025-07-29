<?php

namespace App\Domain\Outcome;

interface Outcome
{
    public function requiresPersistence(): bool;
    public function kind(): OutcomeKind;
}
