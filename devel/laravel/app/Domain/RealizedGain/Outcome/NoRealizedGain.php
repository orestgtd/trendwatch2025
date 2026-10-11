<?php

namespace App\Domain\RealizedGain\Outcome;

use App\Domain\{
    RealizedGain\Model\RealizedGainBasis,
    RealizedGain\Outcome\AbstractRealizedGainOutcome,
};

final class NoRealizedGain extends AbstractRealizedGainOutcome
{
    public static function create(): self
    {
        return new self ();
    }

    public function getRealizedGainBasis(): ?RealizedGainBasis
    {
        return null;
    }
}
