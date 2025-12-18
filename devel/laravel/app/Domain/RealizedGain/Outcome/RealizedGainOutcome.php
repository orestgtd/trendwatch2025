<?php

namespace App\Domain\RealizedGain\Outcome;

use App\Domain\{
    Outcome\Outcome,
    RealizedGain\Model\RealizedGainBasis,
};

interface RealizedGainOutcome extends Outcome
{
    public function getRealizedGainBasis(): ?RealizedGainBasis;

}