<?php

namespace Tests\Unit\Factories;

use App\Domain\Common\{
    Money\Currency,
    Money\MoneyAmount,
};

use App\Domain\Confirmation\ValueObjects\{
    CostAmount,
    ProceedsAmount,
};

use App\Domain\Position\ValueObjects\{
    PositionQuantity,
    PositionType,
};

use App\Domain\Security\ValueObjects\{
    SecurityNumber,
};

use App\Infrastructure\Laravel\Eloquent\Position\{
    Dto\PersistedPositionDto,
};

final class PersistedPositionBuilder
{
    private function __construct(
        private SecurityNumber $securityNumber,
        private PositionType $positionType,
        private PositionQuantity $positionQuantity,
        private CostAmount $totalCost,
        private ProceedsAmount $totalProceeds,
    ) {}

    public static function YYZ(): self
    {
        return new self(
            SecurityNumber::fromString('2112'),
            PositionType::long(),
            PositionQuantity::fromInt(0),
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

    public function build(): PersistedPositionDto
    {
        return new PersistedPositionDto(
            $this->securityNumber,
            $this->positionType,
            $this->positionQuantity,
            $this->totalCost,
            $this->totalProceeds
        );
    }
}
