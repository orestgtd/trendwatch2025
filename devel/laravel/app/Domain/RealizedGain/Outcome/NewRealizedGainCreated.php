<?php

namespace App\Domain\RealizedGain\Outcome;

use App\Domain\Kernel\Identifiers\{
    TradeNumber,
};

use App\Domain\Outcome\Persistence\PersistenceIntent;

use App\Domain\RealizedGain\{
    Model\RealizedGainBasis,
    Outcome\AbstractRealizedGainOutcome,
};

final class NewRealizedGainCreated extends AbstractRealizedGainOutcome
{
    private function __construct(
        private readonly TradeNumber $tradeNumber,
        private readonly RealizedGainBasis $realizedGainBasis
    )
    {
        parent::__construct(PersistenceIntent::insertAll());
    }

    public static function create(
        TradeNumber $tradeNumber,
        RealizedGainBasis $realizedGainBasis,
    ): self
    {
        return new self (
            $tradeNumber,
            $realizedGainBasis
        );
    }

    public function getTradeNumber(): TradeNumber
    {
        return $this->tradeNumber;
    }

    public function getRealizedGainBasis(): RealizedGainBasis
    {
        return $this->realizedGainBasis;
    }
}