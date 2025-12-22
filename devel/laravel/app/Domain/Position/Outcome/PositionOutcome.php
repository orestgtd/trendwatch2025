<?php

namespace App\Domain\Position\Outcome;

use App\Domain\Kernel\Identifiers\{
    TradeNumber,
};

use App\Domain\{
    Outcome\Outcome,
    Position\Model\Position,
};

interface PositionOutcome extends Outcome
{
    public function getPosition(): Position;
    public function tapRealizedGain(callable $action): void;
    public function getTradeNumber(): TradeNumber;
}
