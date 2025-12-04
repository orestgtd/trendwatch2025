<?php

namespace App\Domain\Position\Outcome;

use App\Domain\{
    Confirmation\ValueObjects\TradeNumber,
    Outcome\AbstractOutcome,
    Outcome\OutcomeKind,
    Outcome\Persistence\PersistenceIntent,
    Position\Model\Position,
    RealizedGain\Model\MaybeRealizedGainBasis,
};

abstract class AbstractPositionOutcome
extends AbstractOutcome
implements PositionOutcome
{
    protected readonly Position $position;
    protected readonly TradeNumber $tradeNumber;
    public MaybeRealizedGainBasis $maybeRealizedGainBasis;

    protected function __construct(
        Position $position,
        TradeNumber $tradeNumber,
        PersistenceIntent $persistenceIntent,
    ) {
        $this->position = $position;
        $this->tradeNumber = $tradeNumber;
        $this->maybeRealizedGainBasis = MaybeRealizedGainBasis::create();
        parent::__construct($persistenceIntent);
    }

    public function kind(): OutcomeKind
    {
        return OutcomeKind::POSITION;
    }

    public function getPosition(): Position
    {
        return $this->position;
    }

    public function getTradeNumber(): TradeNumber
    {
        return $this->tradeNumber;
    }

    public function tapRealizedGain(callable $action): void
    {
        $this->maybeRealizedGainBasis->tap($action);
    }
}
