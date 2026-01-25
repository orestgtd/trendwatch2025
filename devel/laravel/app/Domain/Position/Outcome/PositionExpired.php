<?php

namespace App\Domain\Position\Outcome;

use App\Domain\{
    Outcome\AbstractOutcome,
    Outcome\OutcomeKind,
    Outcome\Persistence\PersistenceIntent,
    Position\Model\Position,
};

use App\Domain\RealizedGain\{
    Model\RealizedGainBasis,
    Model\MaybeRealizedGainBasis,
    Outcome\RealizedGainOutcome,
    ValueObjects\RealizationSource,
};

final class PositionExpired extends AbstractOutcome
{
    private function __construct(
        private readonly Position $position,
        private readonly RealizedGainBasis $realizedGainBasis,
    ) {
        parent::__construct(PersistenceIntent::update(['position_quantity']));
    }

    public static function create(
        Position $position,
        RealizedGainBasis $realizedGainBasis,
    ): self {
        return new self($position, $realizedGainBasis);
    }

    public function kind(): OutcomeKind
    {
        return OutcomeKind::POSITION;
    }

    public function getPosition(): Position
    {
        return $this->position;
    }

    public function getRealizationSource(): RealizationSource
    {
        return $this->realizedGainBasis->getRealizationSource();
    }

    // public function getRealizedGainOutcome(): RealizedGainOutcome
    // {
    //     return $this->maybeRealizedGainBasis
    //         ->getOutcomeFromSource($this->realizationSource);
    // }
}
