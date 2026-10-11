<?php

namespace App\Domain\Position\Outcome;

use App\Domain\{
    Outcome\AbstractOutcome,
    Outcome\OutcomeKind,
    Position\Model\Position,
    RealizedGain\Model\MaybeRealizedGainBasis,
    RealizedGain\Outcome\RealizedGainOutcome,
};

abstract class AbstractPositionOutcome
extends AbstractOutcome
implements PositionOutcome
{
    protected readonly Position $position;
    public MaybeRealizedGainBasis $maybeRealizedGainBasis;

    protected function __construct(
        Position $position,
    ) {
        $this->position = $position;
        $this->maybeRealizedGainBasis = MaybeRealizedGainBasis::create();
    }

    public function kind(): OutcomeKind
    {
        return OutcomeKind::POSITION;
    }

    public function getPosition(): Position
    {
        return $this->position;
    }

    public function getRealizedGainOutcome(): RealizedGainOutcome
    {
        return $this->maybeRealizedGainBasis->getOutcome();
    }
}
