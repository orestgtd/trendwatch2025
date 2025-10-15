<?php

namespace App\Domain\Outcome;

use App\Domain\{
    Outcome\Outcome,
    Outcome\OutcomeKind,
    Outcome\Persistence\PersistenceIntent,
};

abstract class AbstractOutcome implements Outcome
{
    protected readonly PersistenceIntent $persistenceIntent;

    protected function __construct(
        PersistenceIntent $persistenceIntent,
    ) {
        $this->persistenceIntent = $persistenceIntent;
    }

    abstract public function kind(): OutcomeKind;

    public function getPersistenceIntent(): PersistenceIntent
    {
        return $this->persistenceIntent;
    }

    public function requiresPersistence(): bool
    {
        return $this->persistenceIntent->requiresPersistence();
    }
}
