<?php

namespace App\Domain\Position\Outcome\Holdings;

use App\Domain\{
    Outcome\Persistence\PersistenceIntent,
    Position\Model\Position,
    Position\Outcome\AbstractPositionOutcome,
};

final class IncreasedHolding extends AbstractPositionOutcome
{
    public function __construct(
        Position $position,
    ) {
        parent::__construct(
            $position,
            PersistenceIntent::update(['position_quantity', 'total_cost'])
        );
    }
}
