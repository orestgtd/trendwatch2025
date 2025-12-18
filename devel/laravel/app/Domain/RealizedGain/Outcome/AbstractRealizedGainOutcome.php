<?php

namespace App\Domain\RealizedGain\Outcome;

use App\Domain\{
    Outcome\AbstractOutcome,
    Outcome\OutcomeKind,
    Outcome\Persistence\PersistenceIntent,
    RealizedGain\Model\RealizedGainBasis,
};

abstract class AbstractRealizedGainOutcome
extends AbstractOutcome
implements RealizedGainOutcome
{
    protected function __construct(
        PersistenceIntent $persistenceIntent,
    ) {
        parent::__construct($persistenceIntent);
    }

    public function kind(): OutcomeKind
    {
        return OutcomeKind::REALIZED_GAIN;
    }

    abstract public function getRealizedGainBasis(): ?RealizedGainBasis;
}