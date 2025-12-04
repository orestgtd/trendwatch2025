<?php

namespace App\Domain\Position\Outcome;

use App\Domain\Confirmation\ValueObjects\TradeNumber;
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
        TradeNumber $tradeNumber,
    ) {
        parent::__construct(
            $position,
            $tradeNumber,
            PersistenceIntent::insertAll()
        );
    }
}
