<?php

namespace App\Domain\Confirmation\Model;

use App\Domain\Confirmation\ValueObjects\{
    PositionEffect,
    TradeAction,
    TradeNumber,
    TradeQuantity,
    UnitPrice,
    Commission,
    UsTax,
};

use App\Domain\Security\ValueObjects\{
    SecurityNumber,
};

/** */

    // public readonly TradeAction $trade_action;
    // public readonly PositionEffect $position_effect;
    // public readonly SecurityNumber $security_number;
    // public readonly Symbol $symbol;
    // public readonly Date $transaction_date;
    // public readonly TradeNumber $trade_number;
    // public readonly string $description;
    // public readonly UnitQuantity $unit_quantity;
    // public readonly UnitType $unit_type;
    // public readonly Money $unit_price;
    // public readonly Money $commission;
    // public readonly Money $us_tax;
    // public readonly ExpirationDate $expiration_date;

/** */

final class Confirmation
{
    private function __construct(
        private SecurityNumber $securityNumber,
        private TradeNumber $tradeNumber,
        private TradeAction $tradeAction,
        private PositionEffect $positionEffect,
        private TradeQuantity $tradeQuantity,
        private UnitPrice $unitPrice,
        private Commission $commission,
        private UsTax $usTax,
    ) {
        $this->securityNumber = $securityNumber;
        $this->tradeNumber = $tradeNumber;
        $this->tradeAction = $tradeAction;
        $this->positionEffect = $positionEffect;
        $this->tradeQuantity = $tradeQuantity;
        $this->unitPrice = $unitPrice;
        $this->commission = $commission;
        $this->usTax = $usTax;
    }

    public static function create(
        SecurityNumber $securityNumber,
        TradeNumber $tradeNumber,
        TradeAction $tradeAction,
        PositionEffect $positionEffect,
        TradeQuantity $tradeQuantity,
        UnitPrice $unitPrice,
        Commission $commission,
        UsTax $usTax,
    ): self {
        return new self(
            $securityNumber,
            $tradeNumber,
            $tradeAction,
            $positionEffect,
            $tradeQuantity,
            $unitPrice,
            $commission,
            $usTax,
        );

    }

    public function getSecurityNumber(): SecurityNumber { return $this->securityNumber; }
    public function getTradeNumber(): TradeNumber { return $this->tradeNumber; }
    public function getTradeAction(): TradeAction { return $this->tradeAction; }
    public function getPositionEffect(): PositionEffect { return $this->positionEffect; }
    public function getTradeQuantity(): TradeQuantity { return $this->tradeQuantity; }
    public function getUnitPrice(): UnitPrice { return $this->unitPrice; }
    public function getCommission(): Commission { return $this->commission; }
    public function getUsTax(): UsTax { return $this->usTax; }
}
