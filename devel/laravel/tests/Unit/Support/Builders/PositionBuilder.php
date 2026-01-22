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
    Values\ExpirationDate,
    Values\PositionType,
    Values\UnitType,
};

use App\Domain\Position\{
    Builders\BuildPositionFromPersisted,
    Model\Position,
};

use App\Shared\Date;

final class PositionBuilder
{
    private function __construct(
        private SecurityNumber $securityNumber,
        private Symbol $symbol,
        private PositionType $positionType,
        private PositionQuantity $positionQuantity,
        private UnitType $unitType,
        private CostAmount $totalCost,
        private ProceedsAmount $totalProceeds,
        private ExpirationDate $expirationDate,
    ) {}

    public static function LongCall(Date $expirationDate): self
    {
        return new self(
            SecurityNumber::fromString('2112'),
            Symbol::fromString('YYZ'),
            PositionType::long(),
            PositionQuantity::fromInt(1),
            UnitType::contracts(),
            CostAmount::zero(Currency::default()),
            ProceedsAmount::zero(Currency::default()),
            ExpirationDate::on($expirationDate)
        );
    }

    public function withSecurityNumber(string $value): self
    {
        $this->securityNumber = SecurityNumber::fromString($value);
        return $this;
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

    public function build(): Position
    {
        return BuildPositionFromPersisted::from(
            $this->securityNumber,
            $this->symbol,
            $this->positionType,
            $this->positionQuantity,
            $this->unitType,
            $this->totalCost,
            $this->totalProceeds,
            $this->expirationDate
        );
    }
}
