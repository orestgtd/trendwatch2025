<?php

namespace App\Domain\RealizedGain\Outcome;

use App\Domain\Confirmation\{
    ValueObjects\TradeNumber,
};

use App\Domain\Outcome\Persistence\PersistenceIntent;

use App\Domain\RealizedGain\{
    Model\RealizedGain,
    Model\RealizedGainBasis,
    Outcome\AbstractRealizedGainOutcome,
};

final class NewRealizedGainCreated extends AbstractRealizedGainOutcome
{
    private function __construct(
        private readonly RealizedGain $realizedGain
    )
    {
        parent::__construct(PersistenceIntent::insertAll());
    }

    public static function create(
        TradeNumber $tradeNumber,
        RealizedGainBasis $basis,
    ): self
    {
        return new self (
            RealizedGain::create(
                $basis->getSecurityNumber(),
                $tradeNumber,
                $basis->getBaseQuantity(),
                $basis->getTradeQuantity(),
                $basis->getCost(),
                $basis->getProceeds(),
            )
        );
    }

    public function getRealizedGain(): RealizedGain
    {
        return $this->realizedGain;
    }
}