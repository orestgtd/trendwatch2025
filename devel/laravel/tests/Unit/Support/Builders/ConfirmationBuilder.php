<?php

namespace Tests\Unit\Support\Builders;

use App\Domain\Confirmation\Model\Confirmation;
use App\Domain\Confirmation\ValueObjects\{
    Commission,
    PositionEffect,
    TradeAction,
    TradeNumber,
    TradeQuantity,
    TradeUnitType,
    UnitPrice,
    UsTax
};

use App\Domain\Security\ValueObjects\SecurityNumber;

use App\Domain\Common\Money\{
    Currency,
    MoneyAmount,
};

final class ConfirmationBuilder
{
    private function __construct(
        private SecurityNumber $securityNumber,
        private TradeNumber $tradeNumber,
        private TradeAction $tradeAction,
        private PositionEffect $positionEffect,
        private TradeQuantity $tradeQuantity,
        private TradeUnitType $tradeUnitType,
        private UnitPrice $unitPrice,
        private Commission $commission,
        private UsTax $usTax,
    ) {}

    public static function buyToOpenShares(): self
    {
        return new self(
            SecurityNumber::fromString('YYZ'),
            TradeNumber::fromString('T000'),
            TradeAction::buy(),
            PositionEffect::open(),
            TradeQuantity::fromInt(100),
            TradeUnitType::shares(),
            UnitPrice::zero(),
            Commission::zero(),
            UsTax::zero()
        );
    }

    public static function sellToOpenShares(): self
    {
        return new self(
            SecurityNumber::fromString('YYZ'),
            TradeNumber::fromString('T000'),
            TradeAction::sell(),
            PositionEffect::open(),
            TradeQuantity::fromInt(100),
            TradeUnitType::shares(),
            UnitPrice::zero(),
            Commission::zero(),
            UsTax::zero()
        );
    }

    public static function buyToCloseShares(): self
    {
        return new self(
            SecurityNumber::fromString('YYZ'),
            TradeNumber::fromString('T000'),
            TradeAction::buy(),
            PositionEffect::close(),
            TradeQuantity::fromInt(100),
            TradeUnitType::shares(),
            UnitPrice::zero(),
            Commission::zero(),
            UsTax::zero()
        );
    }

    public static function sellToCloseShares(): self
    {
        return new self(
            SecurityNumber::fromString('YYZ'),
            TradeNumber::fromString('T000'),
            TradeAction::sell(),
            PositionEffect::close(),
            TradeQuantity::fromInt(100),
            TradeUnitType::shares(),
            UnitPrice::zero(),
            Commission::zero(),
            UsTax::zero()
        );
    }

    public function withQuantity(int $value): self
    {
        $this->tradeQuantity = TradeQuantity::fromInt($value);
        return $this;
    }

    public function withUnitPrice(string $value): self
    {
        $this->unitPrice = UnitPrice::create(
            MoneyAmount::fromString($value),
            Currency::default()
        );
        return $this;
    }

    public function withCommission(string $value): self
    {
        $this->commission = Commission::create(
            MoneyAmount::fromString($value),
            Currency::default()
        );
        return $this;
    }

    public function withUsTax(string $value): self
    {
        $this->usTax = UsTax::create(
            MoneyAmount::fromString($value),
            Currency::default()
        );
        return $this;
    }

    public function build(): Confirmation
    {
        return Confirmation::create(
            $this->securityNumber,
            $this->tradeNumber,
            $this->tradeAction,
            $this->positionEffect,
            $this->tradeQuantity,
            $this->tradeUnitType,
            $this->unitPrice,
            $this->commission,
            $this->usTax,
        );
    }
}
