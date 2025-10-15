<?php

namespace App\Domain\Position\Outcome;

use App\Domain\{
    Outcome\AbstractOutcome,
    Outcome\OutcomeKind,
    Outcome\Persistence\PersistenceIntent,
    Position\Model\Position,
};

abstract class AbstractPositionOutcome
extends AbstractOutcome
implements PositionOutcome
{
    protected readonly Position $position;

    protected function __construct(
        Position $position,
        PersistenceIntent $persistenceIntent,
    ) {
        $this->position = $position;
        parent::__construct($persistenceIntent);
    }

    public function kind(): OutcomeKind
    {
        return OutcomeKind::POSITION;
    }

    public function getPosition(): Position
    {
        return $this->position;
    }
}