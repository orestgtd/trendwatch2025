<?php

namespace App\Domain\Position\Outcome;

use App\Domain\{
    Confirmation\ValueObjects\TradeNumber,
    Outcome\Outcome,
    Position\Model\Position,
    RealizedGain\Outcome\RealizedGainOutcome,
};

interface PositionOutcome extends Outcome
{
    public function getPosition(): Position;
    public function tapRealizedGain(callable $action): void;
    public function getTradeNumber(): TradeNumber;
}
