<?php

namespace App\Domain\Confirmation\Model;

use App\Domain\Calculations\{
    GrossTransactionAmount,
    NetCost,
    TradeBrokerFees,
};

use App\Domain\Confirmation\ValueObjects\{
    CostAmount,
    PositionEffect,
    TradeAction,
    TradeNumber,
    TradeQuantity,
    TradeUnitType,
    UnitPrice,
    Commission,
    UsTax,
};

use App\Domain\Security\ValueObjects\{
    SecurityNumber,
    UnitType,
};

final class Confirmation
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
    ) {
        $this->securityNumber = $securityNumber;
        $this->tradeNumber = $tradeNumber;
        $this->tradeAction = $tradeAction;
        $this->positionEffect = $positionEffect;
        $this->tradeQuantity = $tradeQuantity;
        $this->tradeUnitType = $tradeUnitType;
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
        TradeUnitType $tradeUnitType,
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
            $tradeUnitType,
            $unitPrice,
            $commission,
            $usTax,
        );
    }

    public function getSecurityNumber(): SecurityNumber
    {
        return $this->securityNumber;
    }
    public function getTradeNumber(): TradeNumber
    {
        return $this->tradeNumber;
    }
    public function getTradeAction(): TradeAction
    {
        return $this->tradeAction;
    }
    public function getPositionEffect(): PositionEffect
    {
        return $this->positionEffect;
    }
    public function getTradeQuantity(): TradeQuantity
    {
        return $this->tradeQuantity;
    }
    public function getTradeUnitType(): TradeUnitType
    {
        return $this->tradeUnitType;
    }
    public function getUnitPrice(): UnitPrice
    {
        return $this->unitPrice;
    }
    public function getCommission(): Commission
    {
        return $this->commission;
    }
    public function getUsTax(): UsTax
    {
        return $this->usTax;
    }

    public function netCost(): CostAmount
    {
        $totalBrokerFees = TradeBrokerFees::calculate(
            $this->commission,
            $this->usTax
        );

        $grossTransactionFees = GrossTransactionAmount::calculate(
            $this->tradeQuantity,
            UnitType::shares(),
            $this->unitPrice
        );

        return NetCost::calculate($grossTransactionFees, $totalBrokerFees);
    }
}
