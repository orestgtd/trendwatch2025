<?php

namespace App\Domain\Position\Outcome;

use App\Domain\{
    Confirmation\ValueObjects\TradeNumber,
    Outcome\Persistence\PersistenceIntent,
    Position\Model\Position,
    Position\Outcome\AbstractPositionOutcome,
};

final class IncreasedHolding extends AbstractPositionOutcome
{
    public function __construct(
        Position $position,
        TradeNumber $tradeNumber,
    ) {
        parent::__construct(
            $position,
            $tradeNumber,
            PersistenceIntent::update(['position_quantity', 'total_cost'])
        );
    }
}
