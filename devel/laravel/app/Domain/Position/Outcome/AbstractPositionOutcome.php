<?php

namespace App\Domain\Position\Outcome;

use App\Domain\{
    Outcome\AbstractOutcome,
    Outcome\OutcomeKind,
    Outcome\Persistence\PersistenceIntent,
    Position\Model\Position,
    RealizedGain\Model\MaybeRealizedGainBasis,
};

use App\Domain\RealizedGain\Outcome\RealizedGainOutcome;

use App\Domain\Kernel\Identifiers\{
    TradeNumber,
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

    public function getRealizedGainOutcome(): RealizedGainOutcome
    {
        return $this->maybeRealizedGainBasis->getOutcome($this->tradeNumber);
    }
}
