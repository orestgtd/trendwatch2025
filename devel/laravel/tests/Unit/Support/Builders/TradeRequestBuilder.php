<?php

namespace Tests\Unit\Support\Builders;

use App\Application\ProcessTradeConfirmation\Dto\{
    ParsedTradeRequestDto,
    ValidatedTradeDto,
};
use App\Domain\Confirmation\ValueObjects\Commission;
use App\Domain\Confirmation\ValueObjects\PositionEffect;
use App\Domain\Confirmation\ValueObjects\TradeAction;
use App\Domain\Confirmation\ValueObjects\TradeQuantity;
use App\Domain\Confirmation\ValueObjects\UnitPrice;
use App\Domain\Security\ValueObjects\{
    UnitType,
};

/*
 * 
    SecurityNumber::fromString('AAPL'),
    TradeNumber::fromString('T12345'),
    TradeAction::buy(),
    PositionEffect::open(),
    TradeQuantity::fromInt(100),
    TradeUnitType::shares(),
    UnitPrice::tryFrom('150.25')->getValue(),
    Commission::tryFrom('4.95')->getValue(),
    UsTax::tryFrom('0.00')->getValue(),

 * 
 * 
 */

final class TradeRequestBuilder
{
    private function __construct(
        private string $securityNumber,
        private string $tradeNumber,
        private string $tradeAction,
        private string $positionEffect,
        private int $tradeQuantity,
        private string $tradeUnitType,
        private string $unitPrice,
        private string $commission,
        private string $usTax,
    ) {}

    public static function YYZ(): self
    {
        return new self(
            '2112',
            'T12345',
            TradeAction::BUY,
            PositionEffect::OPEN,
            100,
            UnitType::SHARES,
            '150.25',
            '4.95',
            '0.00',
        );
    }

    // public function withDescription(string $value): self
    // {
    //     $this->description = $value;
    //     return $this;
    // }

    public function build(): ParsedTradeRequestDto
    {
        return ParsedTradeRequestDto::fromValidatedConfirmationDto(
            ValidatedTradeDto::fromArray([
                'security_number' => $this->securityNumber,
                'trade_number' => $this->tradeNumber,
                'trade_action' => $this->tradeAction,
                'position_effect' => $this->positionEffect,
                'trade_quantity' => $this->tradeQuantity,
                'unit_type' => $this->tradeUnitType,
                'unit_price' => $this->unitPrice,
                'commission' => $this->commission,
                'us_tax' => $this->usTax,
            ])->getValue()
        )->getValue();
    }
}
