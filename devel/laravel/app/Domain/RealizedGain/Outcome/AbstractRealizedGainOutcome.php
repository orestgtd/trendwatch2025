<?php

namespace App\Domain\RealizedGain\Outcome;

use App\Domain\{
    Outcome\AbstractOutcome,
    Outcome\OutcomeKind,
    RealizedGain\Model\RealizedGainBasis,
};

abstract class AbstractRealizedGainOutcome
extends AbstractOutcome
implements RealizedGainOutcome
{
    public function kind(): OutcomeKind
    {
        return OutcomeKind::REALIZED_GAIN;
    }

    abstract public function getRealizedGainBasis(): ?RealizedGainBasis;
}