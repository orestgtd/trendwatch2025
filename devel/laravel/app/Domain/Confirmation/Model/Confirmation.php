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
    TradeQuantity,
    UnitPrice,
    Commission,
    UsTax,
};

use App\Domain\Kernel\{
    Identifiers\SecurityNumber,
    Identifiers\Symbol,
    Identifiers\TradeNumber,
    Values\ExpirationDate,
    Values\UnitType,
};

use App\Domain\Security\{
    ValueObjects\Description,
    ValueObjects\SecurityInfo,
};

final class Confirmation
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
    ) {
        $this->securityInfo = $securityInfo;
        $this->tradeNumber = $tradeNumber;
        $this->tradeAction = $tradeAction;
        $this->positionEffect = $positionEffect;
        $this->tradeQuantity = $tradeQuantity;
        $this->unitPrice = $unitPrice;
        $this->commission = $commission;
        $this->usTax = $usTax;
    }

    public static function create(
        SecurityInfo $securityInfo,
        TradeNumber $tradeNumber,
        TradeAction $tradeAction,
        PositionEffect $positionEffect,
        TradeQuantity $tradeQuantity,
        UnitPrice $unitPrice,
        Commission $commission,
        UsTax $usTax,
    ): self {
        return new self(
            $securityInfo,
            $tradeNumber,
            $tradeAction,
            $positionEffect,
            $tradeQuantity,
            $unitPrice,
            $commission,
            $usTax,
        );
    }

    public function getSecurityInfo() : SecurityInfo { return $this->securityInfo; }
    public function getCommission(): Commission { return $this->commission; }
    public function getExpirationDate(): ExpirationDate { return $this->securityInfo->expirationDate; }
    public function getSecurityNumber(): SecurityNumber { return $this->securityInfo->securityNumber; }
    public function getSymbol(): Symbol { return $this->securityInfo->symbol; }
    public function getDescription(): Description { return $this->securityInfo->canonicalDescription; }
    public function getTradeNumber(): TradeNumber { return $this->tradeNumber; }
    public function getPositionEffect(): PositionEffect { return $this->positionEffect; }
    public function getTradeAction(): TradeAction { return $this->tradeAction; }
    public function getTradeQuantity(): TradeQuantity { return $this->tradeQuantity; }
    public function getUnitPrice(): UnitPrice { return $this->unitPrice; }
    public function getUnitType(): UnitType { return $this->securityInfo->unitType; }
    public function getUsTax(): UsTax { return $this->usTax; }

    public function netCost(): CostAmount
    {
        $totalBrokerFees = TradeBrokerFees::calculate(
            $this->commission,
            $this->usTax
        );

        $grossTransactionFees = TransactionValue::calculate(
            $this->tradeQuantity,
            $this->getUnitType(),
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
            $this->getUnitType(),
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
