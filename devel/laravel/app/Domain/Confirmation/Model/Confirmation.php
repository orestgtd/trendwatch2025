<?php

namespace App\Domain\Confirmation\Model;

use App\Domain\Calculations\{
    NetCost,
    NetProceeds,
    TradeBrokerFees,
    TransactionValue,
};

use App\Domain\Confirmation\ValueObjects\{
    CostAmount,
    PositionEffect,
    ProceedsAmount,
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

        $grossTransactionFees = TransactionValue::calculate(
            $this->tradeQuantity,
            UnitType::shares(),
            $this->unitPrice
        );

        return NetCost::calculate($grossTransactionFees->toCost(), $totalBrokerFees);
    }

    public function netProceeds(): ProceedsAmount
    {
        $totalBrokerFees = TradeBrokerFees::calculate(
            $this->commission,
            $this->usTax
        );

        $grossTransactionFees = TransactionValue::calculate(
            $this->tradeQuantity,
            UnitType::shares(),
            $this->unitPrice
        );

        return NetProceeds::calculate($grossTransactionFees->toProceeds(), $totalBrokerFees);
    }

    /**
     * @template T
     * @param callable(Confirmation):T $onOpen
     * @param callable(Confirmation):T $onClose
     * @return T
     */
    public function matchPositionEffect(callable $onOpen, callable $onClose)
    {
        return match ($this->positionEffect->value()) {
            PositionEffect::OPEN => $onOpen($this),
            PositionEffect::CLOSE => $onClose($this),
            default => throw new \LogicException("Unhandled PositionEffect: {$this->positionEffect->value()}"),
        };
    }

    /**
     * @template T
     * @param callable(Confirmation):T $onBuy
     * @param callable(Confirmation):T $onSell
     * @return T
     */
    public function matchTradeAction(callable $onBuy, callable $onSell)
    {
        return match ($this->tradeAction->value()) {
            TradeAction::BUY => $onBuy($this),
            TradeAction::SELL => $onSell($this),
            default => throw new \LogicException("Unhandled TradeAction: {$this->tradeAction->value()}"),
        };
    }
}
