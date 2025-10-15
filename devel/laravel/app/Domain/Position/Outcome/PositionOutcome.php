<?php

namespace App\Domain\Position\Outcome;

use App\Domain\{
    Outcome\Outcome,
    Position\Model\Position,
};

interface PositionOutcome extends Outcome
{
    public function getPosition(): Position;
}
