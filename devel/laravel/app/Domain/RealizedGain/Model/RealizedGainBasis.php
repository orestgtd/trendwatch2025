<?php

namespace App\Domain\RealizedGain\Model;

use App\Domain\Kernel\{
    Identifiers\SecurityNumber,
    Values\PositionType,
    Values\UnitType,
};

use App\Domain\Confirmation\ValueObjects\{
    CostAmount,
    ProceedsAmount,
    TradeQuantity,
};

use App\Domain\Position\{
    ValueObjects\BaseQuantity,
};
use App\Domain\{
    RealizedGain\ValueObjects\RealizationSource,
};

final class RealizedGainBasis
{
    private function __construct(
        private readonly SecurityNumber $securityNumber,
        private readonly PositionType $positionType,
        private readonly RealizationSource $realizationSource,
        private readonly BaseQuantity $baseQuantity,
        private readonly TradeQuantity $tradeQuantity,
        private readonly UnitType $unitType,
        private readonly CostAmount $cost,
        private readonly ProceedsAmount $proceeds,
    ) {
    }

    public static function create(
        SecurityNumber $securityNumber,
        PositionType $positionType,
        RealizationSource $realizationSource,
        BaseQuantity $baseQuantity,
        TradeQuantity $tradeQuantity,
        UnitType $unitType,
        CostAmount $cost,
        ProceedsAmount $proceeds,
    ): self {
        return new self(
            $securityNumber,
            $positionType,
            $realizationSource,
            $baseQuantity,
            $tradeQuantity,
            $unitType,
            $cost,
            $proceeds,
        );
    }

    public function getSecurityNumber(): SecurityNumber { return $this->securityNumber; }
    public function getPositionType(): PositionType { return $this->positionType; }
    public function getRealizationSource(): RealizationSource { return $this->realizationSource; }
    public function getBaseQuantity(): BaseQuantity { return $this->baseQuantity; }
    public function getTradeQuantity(): TradeQuantity { return $this->tradeQuantity; }
    public function getUnitType(): UnitType { return $this->unitType; }
    public function getCost(): CostAmount { return $this->cost; }
    public function getProceeds(): ProceedsAmount { return $this->proceeds; }
}