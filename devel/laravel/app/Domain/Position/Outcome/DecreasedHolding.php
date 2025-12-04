<?php

namespace App\Domain\Position\Outcome;

use App\Domain\Confirmation\{
    ValueObjects\TradeNumber,
};

use App\Domain\Outcome\{
    Persistence\PersistenceIntent,
};

use App\Domain\Position\{
    Model\Position,
    Outcome\AbstractPositionOutcome,
};

use App\Domain\RealizedGain\{
    Model\MaybeRealizedGainBasis,
    Model\RealizedGainBasis,
};

final class DecreasedHolding extends AbstractPositionOutcome
{
    public function __construct(
        Position $position,
        TradeNumber $tradeNumber,
        RealizedGainBasis $realizedGainBasis,
    ) {
        parent::__construct(
            $position,
            $tradeNumber,
            PersistenceIntent::update(['position_quantity', 'total_cost']),
        );

        $this->maybeRealizedGainBasis = MaybeRealizedGainBasis::create($realizedGainBasis);
    }
}
