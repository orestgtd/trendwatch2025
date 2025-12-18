<?php

namespace App\Domain\RealizedGain\Outcome;

use App\Domain\{
    Outcome\Persistence\PersistenceIntent,
    RealizedGain\Model\RealizedGainBasis,
    RealizedGain\Outcome\AbstractRealizedGainOutcome,
};

final class NoRealizedGain extends AbstractRealizedGainOutcome
{
    public function __construct()
    {
        parent::__construct(
            PersistenceIntent::none()
        );
    }

    public function getRealizedGainBasis(): ?RealizedGainBasis
    {
        return null;
    }
}
