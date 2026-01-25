<?php

namespace App\Domain\Position\Outcome;

use App\Domain\{
    Outcome\Outcome,
    Position\Model\Position,
    RealizedGain\Outcome\RealizedGainOutcome,
};

interface PositionOutcome extends Outcome
{
    public function getPosition(): Position;
    public function getRealizedGainOutcome(): RealizedGainOutcome;
}
