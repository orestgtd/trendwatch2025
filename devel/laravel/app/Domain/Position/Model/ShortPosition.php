<?php

namespace App\Domain\Position\Model;

use App\Domain\Calculations\{
    AddCost,
    AddProceeds,
};

use App\Domain\Confirmation\{
    ValueObjects\CostAmount,
    ValueObjects\ProceedsAmount,
    ValueObjects\TradeQuantity,
};

use App\Domain\Position\{
    Model\AbstractPosition,
    ValueObjects\PositionType,
    ValueObjects\PositionQuantity,
};

use App\Domain\Security\{
    ValueObjects\SecurityNumber,
};

final class ShortPosition extends AbstractPosition
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
        return PositionType::short();
    }

    public function increaseHolding(TradeQuantity $change, ProceedsAmount $tradeProceeds): static
    {
        $this->positionQuantity = PositionQuantity::fromInt(
            $this->positionQuantity->value()
                + $change->value()
        );

        $this->totalProceeds = AddProceeds::calculate($this->totalProceeds, $tradeProceeds);

        return $this;
    }

    public function decreaseHolding(TradeQuantity $change, CostAmount $tradeCost): static
    {
        $this->positionQuantity = PositionQuantity::fromInt(
            $this->positionQuantity->value()
                - $change->value()
        );

        $this->totalCost = AddCost::calculate($this->totalCost, $tradeCost);

        return $this;
    }

    // /** @return Result<LongPosition> */
    // public function applyTrade(Confirmation $confirmation): Result
    // {
    //     if ($confirmation->isOpen()) {
    //         $this->increase($confirmation->getTradeQuantity());
    //     } else {
    //         $result = $this->decrease($confirmation->getTradeQuantity());
    //         if ($this->quantity->isZero()) {
    //             $this->markClosed();
    //         }
    //         return $result;
    //     }
    //     return Result::success($this);
    // }

    // public function increase(TradeQuantity $change): void
    // {

    // }

    // public function decrease(TradeQuantity $change): Result
    // {
    //     return Result::success($this);
    // }

    public function markClosed(): void {}

    public function isClosed(): bool
    {
        return false;
    }

    // public function type(): string
    // {
    //     return 'short';
    // }

}
