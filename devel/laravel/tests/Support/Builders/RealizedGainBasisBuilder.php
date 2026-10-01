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
    Values\RealizationDate,
    Values\UnitType,
};
use App\Domain\{
    Confirmation\ValueObjects\TradeQuantity,
    Kernel\Identifiers\TradeNumber,
    Position\ValueObjects\BaseQuantity,
    RealizedGain\Model\RealizedGainBasis,
    RealizedGain\ValueObjects\RealizationSource,
};

use App\Foundation\{
    Date,
};

use App\Infrastructure\{
    Laravel\Eloquent\RealizedGain\Dto\PersistedRealizedGainBasisDto,
};

final class RealizedGainBasisBuilder
{
    private function __construct(
        private SecurityNumber $securityNumber,
        private RealizationDate $realizationDate,
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
            RealizationDate::from(Date::fromString('2026-01-31')),
            PositionType::long(),
            RealizationSource::trade(TradeNumber::fromString('T0001')),
            BaseQuantity::fromInt(100),
            TradeQuantity::fromInt(100),
            UnitType::shares(),
            CostAmount::create(MoneyAmount::fromString('28500.00'), Currency::default()),
            ProceedsAmount::create(MoneyAmount::fromString('30250.00'), Currency::default()),
        );
    }

    public function withSecurityNumber(string $value): self
    {
        $this->securityNumber = SecurityNumber::fromString($value);
        return $this;
    }

    public function withRealizationDate(string $value): self
    {
        $this->realizationDate = RealizationDate::from(Date::fromString($value));
        return $this;
    }

    public function withCost(string $value): self
    {
        $this->totalCost = CostAmount::create(
            MoneyAmount::fromString($value),
            Currency::default());

        return $this;
    }

    public function withProceeds(string $value): self
    {
        $this->totalProceeds = ProceedsAmount::create(
            MoneyAmount::fromString($value),
            Currency::default());

        return $this;
    }

    public function build(): RealizedGainBasis
    {
        return RealizedGainBasis::create(
            $this->securityNumber,
            $this->realizationDate,
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
            $this->realizationDate,
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
