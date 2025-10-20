<?php

namespace App\Domain\Position\Outcome;

use App\Domain\Outcome\{
    Persistence\PersistenceIntent,
};

use App\Domain\Position\{
    Model\Position,
    Outcome\AbstractPositionOutcome,
};

final class NewPositionCreated extends AbstractPositionOutcome
{
    public function __construct(
        Position $position,
    ) {
        parent::__construct(
            $position,
            PersistenceIntent::insertAll()
        );
    }

}
