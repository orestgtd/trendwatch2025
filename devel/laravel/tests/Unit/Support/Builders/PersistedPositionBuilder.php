<?php

namespace Tests\Unit\Support\Builders;
 
use App\Domain\Confirmation\ValueObjects\{
    CostAmount,
    ProceedsAmount,
};

use App\Domain\Position\ValueObjects\{
    PositionQuantity,
};

use App\Domain\Kernel\{
    Identifiers\SecurityNumber,
    Identifiers\Symbol,
    Money\Currency,
    Money\MoneyAmount,
    Values\PositionType,
    Values\UnitType,
};

use App\Infrastructure\Laravel\Eloquent\Position\{
    Dto\PersistedPositionDto,
};

final class PersistedPositionBuilder
{
    private function __construct(
        private SecurityNumber $securityNumber,
        private Symbol $symbol,
        private PositionType $positionType,
        private PositionQuantity $positionQuantity,
        private UnitType $unitType,
        private CostAmount $totalCost,
        private ProceedsAmount $totalProceeds,
    ) {}

    public static function YYZ(): self
    {
        return new self(
            SecurityNumber::fromString('2112'),
            Symbol::fromString('YYZ'),
            PositionType::long(),
            PositionQuantity::fromInt(0),
            UnitType::shares(),
            CostAmount::zero(Currency::default()),
            ProceedsAmount::zero(Currency::default())
        );
    }

    public static function YYZShort(): self
    {
        return new self(
            SecurityNumber::fromString('2112'),
            Symbol::fromString('YYZ'),
            PositionType::short(),
            PositionQuantity::fromInt(0),
            UnitType::shares(),
            CostAmount::zero(Currency::default()),
            ProceedsAmount::zero(Currency::default())
        );
    }

    public function withQuantity(int $value): self
    {
        $this->positionQuantity = PositionQuantity::fromInt($value);
        return $this;
    }

    public function withTotalCost(string $value): self
    {
        $this->totalCost = CostAmount::create(
            MoneyAmount::fromString($value),
            Currency::default()
        );
        return $this;
    }

    public function withTotalProceeds(string $value): self
    {
        $this->totalProceeds = ProceedsAmount::create(
            MoneyAmount::fromString($value),
            Currency::default()
        );
        return $this;
    }

    public function build(): PersistedPositionDto
    {
        return new PersistedPositionDto(
            $this->securityNumber,
            $this->symbol,
            $this->positionType,
            $this->positionQuantity,
            $this->unitType,
            $this->totalCost,
            $this->totalProceeds
        );
    }
}
