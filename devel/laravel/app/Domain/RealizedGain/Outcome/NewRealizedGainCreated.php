<?php

namespace App\Domain\RealizedGain\Outcome;

use App\Domain\Kernel\Identifiers\{
    TradeNumber,
};

use App\Domain\RealizedGain\{
    ValueObjects\RealizationSource,
};

use App\Domain\Outcome\Persistence\PersistenceIntent;

use App\Domain\RealizedGain\{
    Model\RealizedGainBasis,
    Outcome\AbstractRealizedGainOutcome,
};

final class NewRealizedGainCreated extends AbstractRealizedGainOutcome
{
    private function __construct(
        private readonly RealizedGainBasis $realizedGainBasis
    )
    {
        parent::__construct(PersistenceIntent::insertAll());
    }

    public static function create(
        RealizedGainBasis $realizedGainBasis,
    ): self
    {
        return new self (
            $realizedGainBasis
        );
    }

    public function getSource(): RealizationSource
    {
        return $this->realizedGainBasis->getRealizationSource();
    }

    public function getRealizedGainBasis(): RealizedGainBasis
    {
        return $this->realizedGainBasis;
    }
}