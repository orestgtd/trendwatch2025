<?php

namespace Tests\Unit\Support\Builders;

use App\Application\ProcessTradeConfirmation\Dto\{
    ParsedTradeRequestDto,
    ValidatedTradeDto,
};

use App\Domain\Confirmation\{
    ValueObjects\PositionEffect,
    ValueObjects\TradeAction,
};

use App\Domain\Kernel\{
    Values\UnitType,
};

final class TradeRequestBuilder
{
    private function __construct(
        private string $securityNumber,
        private string $tradeNumber,
        private string $tradeAction,
        private string $positionEffect,
        private int $tradeQuantity,
        private string $unitType,
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
                'unit_type' => $this->unitType,
                'unit_price' => $this->unitPrice,
                'commission' => $this->commission,
                'us_tax' => $this->usTax,
            ])->getValue()
        )->getValue();
    }
}
