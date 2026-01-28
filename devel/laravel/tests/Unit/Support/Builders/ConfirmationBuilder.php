<?php

namespace Tests\Unit\Support\Builders;

use App\Domain\Confirmation\Model\Confirmation;

use App\Domain\Confirmation\ValueObjects\{
    Commission,
    PositionEffect,
    TradeAction,
    TradeQuantity,
    UnitPrice,
    UsTax
};

use App\Domain\Kernel\{
    Identifiers\SecurityNumber,
    Identifiers\Symbol,
    Identifiers\TradeNumber,
    Money\Currency,
    Money\MoneyAmount,
    Values\ExpirationDate,
    Values\UnitType,
};

use App\Domain\Security\{
    ValueObjects\Description,
    ValueObjects\SecurityInfo,
};

final class ConfirmationBuilder
{
    private function __construct(
        private SecurityInfo $securityInfo,
        private TradeNumber $tradeNumber,
        private TradeAction $tradeAction,
        private PositionEffect $positionEffect,
        private TradeQuantity $tradeQuantity,
        private UnitPrice $unitPrice,
        private Commission $commission,
        private UsTax $usTax,
    ) {}

    public static function buyToOpenShares(): self
    {
        return new self(
            SecurityInfo::from(
                SecurityNumber::fromString('2112'),
                Symbol::fromString('YYZ'),
                Description::fromString('Security Under Pressure'),
                UnitType::shares(),
                ExpirationDate::never()
            ),
            TradeNumber::fromString('T000'),
            TradeAction::buy(),
            PositionEffect::open(),
            TradeQuantity::fromInt(100),
            UnitPrice::zero(),
            Commission::zero(),
            UsTax::zero(),
        );
    }

    public static function sellToOpenShares(): self
    {
        return new self(
            SecurityInfo::from(
                SecurityNumber::fromString('2112'),
                Symbol::fromString('YYZ'),
                Description::fromString('Security Under Pressure'),
                UnitType::shares(),
                ExpirationDate::never()
            ),
            TradeNumber::fromString('T000'),
            TradeAction::sell(),
            PositionEffect::open(),
            TradeQuantity::fromInt(100),
            UnitPrice::zero(),
            Commission::zero(),
            UsTax::zero(),
        );
    }

    public static function buyToCloseShares(): self
    {
        return new self(
            SecurityInfo::from(
                SecurityNumber::fromString('2112'),
                Symbol::fromString('YYZ'),
                Description::fromString('Security Under Pressure'),
                UnitType::shares(),
                ExpirationDate::never()
            ),
            TradeNumber::fromString('T000'),
            TradeAction::buy(),
            PositionEffect::close(),
            TradeQuantity::fromInt(100),
            UnitPrice::zero(),
            Commission::zero(),
            UsTax::zero(),
        );
    }

    public static function sellToCloseShares(): self
    {
        return new self(
            SecurityInfo::from(
                SecurityNumber::fromString('2112'),
                Symbol::fromString('YYZ'),
                Description::fromString('Security Under Pressure'),
                UnitType::shares(),
                ExpirationDate::never()
            ),
            TradeNumber::fromString('T000'),
            TradeAction::sell(),
            PositionEffect::close(),
            TradeQuantity::fromInt(100),
            UnitPrice::zero(),
            Commission::zero(),
            UsTax::zero(),
        );
    }

    public function withTradeNumber(string $value): self
    {
        $this->tradeNumber = TradeNumber::fromString($value);
        return $this;
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
            $this->securityInfo,
            $this->tradeNumber,
            $this->tradeAction,
            $this->positionEffect,
            $this->tradeQuantity,
            $this->unitPrice,
            $this->commission,
            $this->usTax,
        );
    }
}
