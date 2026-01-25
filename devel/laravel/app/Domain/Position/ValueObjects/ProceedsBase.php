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

final class ProceedsBase
{
    private BaseQuantity $baseQuantity;
    private CostAmount $totalCost;
    private ProceedsAmount $totalProceeds;

    public function __construct(
        BaseQuantity $baseQuantity,
        ProceedsAmount $totalProceeds,
    ) {
        $this->baseQuantity = $baseQuantity;
        $this->totalProceeds = $totalProceeds;
        $this->totalCost = CostAmount::zero();
    }

    public static function create(
        BaseQuantity $baseQuantity,
        ProceedsAmount $totalProceeds,
    ): self
    {
        return new self($baseQuantity, $totalProceeds);
    }

    public static function fromPersisted(
        BaseQuantity $baseQuantity,
        ProceedsAmount $totalProceeds,
        CostAmount $totalCost,
    ): self {
        $instance = new self($baseQuantity, $totalProceeds);
        $instance->totalCost = $totalCost;
        return $instance;
    }

    public function addShortCover(TradeQuantity $change, CostAmount $tradeCost): self
    {
        $this->baseQuantity = BaseQuantity::fromInt(
            $this->baseQuantity->value()
            - $change->value()
        );

        $this->totalCost = AddCost::calculate($this->totalCost, $tradeCost);

        return $this;
    }

    public function addShortSale(TradeQuantity $tradeQuantity, ProceedsAmount $tradeProceeds): self
    {
        $this->baseQuantity = BaseQuantity::fromInt(
            $this->baseQuantity->value()
            + $tradeQuantity->value()
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