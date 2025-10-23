<?php

namespace Tests\Unit\Support\Factories;

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

final class ConfirmationFactory
{
    public static function T12345(): Confirmation
    {
        return Confirmation::create(
            SecurityNumber::fromString('AAPL'),
            TradeNumber::fromString('T12345'),
            TradeAction::buy(),
            PositionEffect::open(),
            TradeQuantity::fromInt(100),
            TradeUnitType::shares(),
            UnitPrice::tryFrom('150.25')->getValue(),
            Commission::tryFrom('4.95')->getValue(),
            UsTax::tryFrom('0.00')->getValue(),
        );
    }

    public static function T12346(): Confirmation
    {
        return Confirmation::create(
            SecurityNumber::fromString('AAPL'),
            TradeNumber::fromString('T12346'),
            TradeAction::buy(),
            PositionEffect::open(),
            TradeQuantity::fromInt(100),
            TradeUnitType::shares(),
            UnitPrice::tryFrom('150.25')->getValue(),
            Commission::tryFrom('4.95')->getValue(),
            UsTax::tryFrom('0.00')->getValue(),
        );
    }

    public static function TShort1(): Confirmation
    {
        return Confirmation::create(
            SecurityNumber::fromString('AAPL'),
            TradeNumber::fromString('TShort1'),
            TradeAction::sell(),
            PositionEffect::open(),
            TradeQuantity::fromInt(400),
            TradeUnitType::shares(),
            UnitPrice::tryFrom('400')->getValue(),
            Commission::tryFrom('4.95')->getValue(),
            UsTax::tryFrom('0.00')->getValue(),
        );
    }

    public static function T12347(): Confirmation
    {
        return Confirmation::create(
            SecurityNumber::fromString('MSFT'),
            TradeNumber::fromString('T12346'),
            TradeAction::sell(),
            PositionEffect::close(),
            TradeQuantity::fromInt(50),
            TradeUnitType::shares(),
            UnitPrice::tryFrom('250.00')->getValue(),
            Commission::tryFrom('5.00')->getValue(),
            UsTax::tryFrom('0.00')->getValue(),
        );
    }
}
