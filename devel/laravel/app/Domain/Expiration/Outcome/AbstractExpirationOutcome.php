<?php

namespace App\Domain\Expiration\Outcome;

use App\Domain\{
    Outcome\AbstractOutcome,
    Outcome\OutcomeKind,
    Position\Model\Position,
    Position\ValueObjects\PositionQuantity,
    RealizedGain\Outcome\RealizedGainOutcome,
};

abstract class AbstractExpirationOutcome
extends AbstractOutcome
implements ExpirationOutcome
{
    protected readonly Position $position;

    abstract public function getRealizedGainOutcome(): RealizedGainOutcome;

    protected function __construct(
        Position $position,
    ) {
        $this->position = $position;
    }

    public function kind(): OutcomeKind
    {
        return OutcomeKind::EXPIRATION;
    }

    public function getPosition(): Position
    {
        return $this->position;
    }

    public function getPositionQuantity(): PositionQuantity
    {
        return $this->position->getPositionQuantity();
    }
}
