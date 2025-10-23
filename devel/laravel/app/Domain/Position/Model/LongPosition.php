<?php

namespace App\Domain\Position\Model;

use App\Domain\Calculations\{
    AddCost,
    AddProceeds,
};

use App\Domain\Confirmation\ValueObjects\{
    CostAmount,
    ProceedsAmount,
    TradeQuantity,
};

use App\Domain\Position\{
    Model\AbstractPosition,
    ValueObjects\PositionType,
    ValueObjects\PositionQuantity,
};

use App\Domain\Security\{
    ValueObjects\SecurityNumber,
};

final class LongPosition extends AbstractPosition
{
    private function __construct(
        SecurityNumber $securityNumber,
        PositionQuantity $positionQuantity,
        CostAmount $totalCost,
        ProceedsAmount $totalProceeds,
    ) {
        $this->securityNumber = $securityNumber;
        $this->positionQuantity = $positionQuantity;
        $this->totalCost = $totalCost;
        $this->totalProceeds = $totalProceeds;
    }

    public static function create(
        SecurityNumber $securityNumber,
        PositionQuantity $positionQuantity,
        CostAmount $totalCost,
        ProceedsAmount $totalProceeds,
    ): self {
        return new self(
            $securityNumber,
            $positionQuantity,
            $totalCost,
            $totalProceeds,
        );
    }

    public function getPositionType(): PositionType
    {
        return PositionType::long();
    }

    public function increaseHolding(TradeQuantity $change, CostAmount $tradeCost): static
    {
        $this->positionQuantity = PositionQuantity::fromInt(
            $this->positionQuantity->value()
            + $change->value()
        );

        $this->totalCost = AddCost::calculate($this->totalCost, $tradeCost);

        return $this;
    }

    public function decreaseHolding(TradeQuantity $change, ProceedsAmount $tradeProceeds): static
    {
        $this->positionQuantity = PositionQuantity::fromInt(
            $this->positionQuantity->value()
            - $change->value()
        );

        // if ($this->positionQuantity->isZero()) {
        //     $this->markClosed();
        // }

        $this->totalProceeds = AddProceeds::calculate($this->totalProceeds, $tradeProceeds);

        return $this;
    }

    public function markClosed(): void {}

    public function isClosed(): bool
    {
        return false;
    }
}
