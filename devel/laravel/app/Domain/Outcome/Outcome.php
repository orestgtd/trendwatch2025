<?php

namespace App\Domain\Outcome;

use App\Domain\Outcome\Persistence\PersistenceIntent;

interface Outcome
{
    public function kind(): OutcomeKind;
    public function getPersistenceIntent(): PersistenceIntent;
    public function requiresPersistence(): bool;

}
