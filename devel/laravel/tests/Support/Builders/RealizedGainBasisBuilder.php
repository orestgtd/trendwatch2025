<?php

namespace Tests\Support\Builders;
 
use App\Domain\Confirmation\ValueObjects\{
    CostAmount,
    ProceedsAmount,
};

use App\Domain\Kernel\{
    Identifiers\SecurityNumber,
    Money\Currency,
    Money\MoneyAmount,
    Values\PositionType,
    Values\UnitType,
};
use App\Domain\{
    Confirmation\ValueObjects\TradeQuantity,
    Kernel\Identifiers\TradeNumber,
    Position\ValueObjects\BaseQuantity,
    RealizedGain\Model\RealizedGainBasis,
    RealizedGain\ValueObjects\RealizationSource,
};

use App\Infrastructure\{
    Laravel\Eloquent\RealizedGain\Dto\PersistedRealizedGainBasisDto,
};

final class RealizedGainBasisBuilder
{
    private function __construct(
        private SecurityNumber $securityNumber,
        private PositionType $positionType,
        private RealizationSource $realizationSource,
        private BaseQuantity $baseQuantity,
        private TradeQuantity $tradeQuantity,
        private UnitType $unitType,
        private CostAmount $totalCost,
        private ProceedsAmount $totalProceeds,
    ) {}

    public static function gainFromBuyToCloseLongPosition(): self
    {
        return new self(
            SecurityNumber::fromString('MSFT'),
            PositionType::long(),
            RealizationSource::trade(TradeNumber::fromString('T0001')),
            BaseQuantity::fromInt(100),
            TradeQuantity::fromInt(100),
            UnitType::shares(),
            CostAmount::create(MoneyAmount::fromString('28500.0'), Currency::default()),
            ProceedsAmount::create(MoneyAmount::fromString('30250.0'), Currency::default()),
        );
    }

    public function build(): RealizedGainBasis
    {
        return RealizedGainBasis::create(
            $this->securityNumber,
            $this->positionType,
            $this->realizationSource,
            $this->baseQuantity,
            $this->tradeQuantity,
            $this->unitType,
            $this->totalCost,
            $this->totalProceeds,
        );
    }

    public function buildPersistedDto(): PersistedRealizedGainBasisDto
    {
        return new PersistedRealizedGainBasisDto(
            $this->securityNumber,
            $this->positionType,
            $this->realizationSource,
            $this->baseQuantity,
            $this->tradeQuantity,
            $this->unitType,
            $this->totalCost,
            $this->totalProceeds,
        );
    }
}
