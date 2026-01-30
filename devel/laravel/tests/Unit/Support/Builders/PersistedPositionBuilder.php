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

use App\Domain\Security\{
    Expiration\ExpirationRule,
    ValueObjects\Description,
    ValueObjects\SecurityInfo,
};

use App\Infrastructure\Laravel\Eloquent\Position\{
    Dto\PersistedPositionDto,
};

use App\Shared\Date;

final class PersistedPositionBuilder
{
    private function __construct(
        private SecurityNumber $securityNumber,
        private Symbol $symbol,
        private Description $description,
        private PositionType $positionType,
        private PositionQuantity $positionQuantity,
        private UnitType $unitType,
        private CostAmount $totalCost,
        private ProceedsAmount $totalProceeds,
        private ExpirationRule $expirationRule,
    ) {}

    public static function YYZ(): self
    {
        return new self(
            SecurityNumber::fromString('2112'),
            Symbol::fromString('YYZ'),
            Description::fromString('Security Under Pressure'),
            PositionType::long(),
            PositionQuantity::fromInt(0),
            UnitType::shares(),
            CostAmount::zero(Currency::default()),
            ProceedsAmount::zero(Currency::default()),
            ExpirationRule::neverExpires()
        );
    }

    public static function YYZShort(): self
    {
        return new self(
            SecurityNumber::fromString('2112'),
            Symbol::fromString('YYZ'),
            Description::fromString('Security Under Pressure'),
            PositionType::short(),
            PositionQuantity::fromInt(0),
            UnitType::shares(),
            CostAmount::zero(Currency::default()),
            ProceedsAmount::zero(Currency::default()),
            ExpirationRule::neverExpires()
        );
    }

    public static function LongCall(Date $expirationDate): self
    {
        return new self(
            SecurityNumber::fromString('2112.R40'),
            Symbol::fromString('YYZ'),
            Description::fromString('CALL Security Under Pressure'),
            PositionType::long(),
            PositionQuantity::fromInt(1),
            UnitType::contracts(),
            CostAmount::zero(Currency::default()),
            ProceedsAmount::zero(Currency::default()),
            ExpirationRule::expiresOn(ExpirationDate::from($expirationDate)),
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
            SecurityInfo::from(
                $this->securityNumber,
                $this->symbol,
                $this->description,
                $this->unitType,
                $this->expirationRule
            ),
            $this->positionType,
            $this->positionQuantity,
            $this->totalCost,
            $this->totalProceeds,
        );
    }
}
