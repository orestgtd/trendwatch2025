<?php

namespace App\Domain\Position\ValueObjects;

use App\Domain\{
    Calculations\AddCost,
    Calculations\AddProceeds,
    Confirmation\ValueObjects\CostAmount,
    Confirmation\ValueObjects\ProceedsAmount,
    Confirmation\ValueObjects\TradeQuantity,
    Position\ValueObjects\BaseQuantity,
};

final class CostBase
{
    private BaseQuantity $baseQuantity;
    private CostAmount $totalCost;
    private ProceedsAmount $totalProceeds;

    public function __construct(
        BaseQuantity $baseQuantity,
        CostAmount $totalCost,
    ) {
        $this->baseQuantity = $baseQuantity;
        $this->totalCost = $totalCost;
        $this->totalProceeds = ProceedsAmount::zero();
    }

    public static function create(
        BaseQuantity $baseQuantity,
        CostAmount $totalCost,
    ): self
    {
        return new self($baseQuantity, $totalCost);
    }

    public static function fromPersisted(
        BaseQuantity $baseQuantity,
        CostAmount $totalCost,
        ProceedsAmount $totalProceeds,
    ): self {
        $instance = new self($baseQuantity, $totalCost);
        $instance->totalProceeds = $totalProceeds;

        return $instance;
    }

    public function addPurchase(TradeQuantity $change, CostAmount $tradeCost): self
    {
        $this->baseQuantity = BaseQuantity::fromInt(
            $this->baseQuantity->value()
            + $change->value()
        );

        $this->totalCost = AddCost::calculate($this->totalCost, $tradeCost);

        return $this;
    }

    public function addSale(TradeQuantity $tradeQuantity, ProceedsAmount $tradeProceeds): self
    {
        $this->baseQuantity = BaseQuantity::fromInt(
            $this->baseQuantity->value()
            - $tradeQuantity->value()
        );

        $this->totalProceeds = AddProceeds::calculate($this->totalProceeds, $tradeProceeds);

        return $this;
    }

    public function expire(): self
    {
        $this->baseQuantity = BaseQuantity::zero();

        return $this;
    }

    public function getQuantity(): BaseQuantity { return $this->baseQuantity; }
    public function getTotalCost(): CostAmount { return $this->totalCost; }
    public function getTotalProceeds(): ProceedsAmount { return $this->totalProceeds; }

}