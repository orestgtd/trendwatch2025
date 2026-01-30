<?php

namespace App\Domain\Expiration\Outcome;

use App\Domain\{
    Outcome\Outcome,
    Position\Model\Position,
    Position\ValueObjects\PositionQuantity,
    RealizedGain\Outcome\RealizedGainOutcome,
};

interface ExpirationOutcome extends Outcome
{
    public function getPosition(): Position;
    public function getRealizedGainOutcome(): RealizedGainOutcome;
    public function getPositionQuantity(): PositionQuantity;
}
