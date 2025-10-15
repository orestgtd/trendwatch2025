<?php

namespace App\Domain\Position\Model;

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

use App\Shared\Result;

final class LongPosition extends AbstractPosition
{
    private function __construct(
        SecurityNumber $securityNumber,
        PositionQuantity $positionQuantity,
        CostAmount $totalCost,
        ProceedsAmount $totalProceeds,
    ){
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
    ): self
    {
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

    public function increaseHolding(TradeQuantity $change): static
    {
        $this->positionQuantity = PositionQuantity::fromInt(
            $this->positionQuantity->value()
            + $change->value()
        );
        return $this;
    }

    public function decrease(TradeQuantity $change): Result
    {
        return Result::success($this);
    }

    public function markClosed(): void
    {

    }

    public function isClosed(): bool
    {
        return false;
    }

    // public function type(): string
    // {
    //     return 'long';
    // }

}
