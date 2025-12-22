<?php

namespace App\Domain\RealizedGain\Model;

use App\Domain\Kernel\Identifiers\{
    SecurityNumber,
    TradeNumber,
};
use App\Domain\Confirmation\ValueObjects\{
    CostAmount,
    ProceedsAmount,
    TradeQuantity,
};

use App\Domain\Position\{
    ValueObjects\BaseQuantity,
};

use App\Domain\RealizedGain\Outcome\NewRealizedGainCreated;

final class RealizedGainBasis
{
    private function __construct(
        private readonly SecurityNumber $securityNumber,
        private readonly BaseQuantity $baseQuantity,
        private readonly TradeQuantity $tradeQuantity,
        private readonly CostAmount $cost,
        private readonly ProceedsAmount $proceeds,
    ) {
    }

    public static function create(
        SecurityNumber $securityNumber,
        BaseQuantity $baseQuantity,
        TradeQuantity $tradeQuantity,
        CostAmount $cost,
        ProceedsAmount $proceeds,
    ): self {
        return new self(
            $securityNumber,
            $baseQuantity,
            $tradeQuantity,
            $cost,
            $proceeds,
        );
    }

    public function getSecurityNumber(): SecurityNumber { return $this->securityNumber; }
    public function getBaseQuantity(): BaseQuantity { return $this->baseQuantity; }
    public function getTradeQuantity(): TradeQuantity { return $this->tradeQuantity; }
    public function getCost(): CostAmount { return $this->cost; }
    public function getProceeds(): ProceedsAmount { return $this->proceeds; }

    public function toRealizedGainOutcome(
        TradeNumber $tradeNumber
    ): NewRealizedGainCreated {
        return NewRealizedGainCreated::create($tradeNumber, $this);
    }
}