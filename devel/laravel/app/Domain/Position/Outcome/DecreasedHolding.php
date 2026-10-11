<?php
 
namespace App\Domain\Position\Outcome;

use App\Domain\Position\{
    Model\Position,
    Outcome\AbstractPositionOutcome,
};
 
use App\Domain\RealizedGain\{
    Model\MaybeRealizedGainBasis,
    Model\RealizedGainBasis,
    Outcome\NewRealizedGainCreated,
    Outcome\RealizedGainOutcome,
};

final class DecreasedHolding extends AbstractPositionOutcome
{
    public function __construct(
        Position $position,
        private readonly RealizedGainBasis $realizedGainBasis,
    ) {
        parent::__construct(
            $position,
        );

        $this->maybeRealizedGainBasis = MaybeRealizedGainBasis::create($realizedGainBasis);
    }

    public function getRealizedGainOutcome(): RealizedGainOutcome
    {
        return NewRealizedGainCreated::create($this->realizedGainBasis);
    }
}
