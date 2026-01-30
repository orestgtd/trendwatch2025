<?php

namespace App\Domain\Position\Outcome\Holdings;

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
    Outcome\RealizedGainOutcome,
};
use App\Domain\RealizedGain\Outcome\NewRealizedGainCreated;

final class DecreasedHolding extends AbstractPositionOutcome
{
    public function __construct(
        Position $position,
        private readonly RealizedGainBasis $realizedGainBasis,
    ) {
        parent::__construct(
            $position,
            PersistenceIntent::update(['position_quantity', 'total_cost']),
        );

        $this->maybeRealizedGainBasis = MaybeRealizedGainBasis::create($realizedGainBasis);
    }

    public function getRealizedGainOutcome(): RealizedGainOutcome
    {
        return NewRealizedGainCreated::create($this->realizedGainBasis);
    }

}
