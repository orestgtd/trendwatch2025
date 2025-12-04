<?php

namespace App\Domain\RealizedGain\Outcome;

use App\Domain\{
    Model\NoGainBasis,
    Model\RealizedGainBasis,
    Outcome\Persistence\PersistenceIntent,
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

    // public function hasRealizedGain(): bool
    // {
    //     return false;
    // }
}
