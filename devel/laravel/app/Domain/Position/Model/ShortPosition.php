<?php

namespace App\Domain\Position\Model;

use App\Domain\Confirmation\{
    ValueObjects\CostAmount,
    ValueObjects\ProceedsAmount,
    ValueObjects\TradeQuantity,
};

use App\Domain\Position\{
    Model\AbstractPosition,
    ValueObjects\BaseQuantity,
    ValueObjects\PositionType,
    ValueObjects\PositionQuantity,
    ValueObjects\ProceedsBase,
};

use App\Domain\Security\{
    ValueObjects\SecurityNumber,
};

final class ShortPosition extends AbstractPosition
{
    private ProceedsBase $proceedsBase;
    // private CostAmount $totalCost;

    private function __construct(
        SecurityNumber $securityNumber,
        PositionQuantity $positionQuantity,
        // CostAmount $totalCost,
        ProceedsAmount $totalProceeds,
    ) {
        $this->securityNumber = $securityNumber;
        $this->proceedsBase = ProceedsBase::create(
            BaseQuantity::fromPositionQuantity($positionQuantity),
            $totalProceeds,
        );

        // $this->totalCost = $totalCost;
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
            // $totalCost,
            $totalProceeds,
        );
    }

    public function getPositionType(): PositionType
    {
        return PositionType::short();
    }

    public function getBaseQuantity(): BaseQuantity
    {
        return $this->proceedsBase->getQuantity();
    }

    public function getPositionQuantity(): PositionQuantity
    {
        return PositionQuantity::fromBaseQuantity(
            $this->proceedsBase->getQuantity()
        );
    }

    public function getTotalCost(): CostAmount
    {
        return $this->proceedsBase->getTotalCost();
    }

    public function getTotalProceeds(): ProceedsAmount
    {
        return $this->proceedsBase->getTotalProceeds();
    }

    public function addCover(TradeQuantity $change, CostAmount $tradeCost): static
    {
        $this->proceedsBase->addShortCover($change, $tradeCost);

        return $this;
    }

    public function increaseHolding(TradeQuantity $change, ProceedsAmount $tradeProceeds): static
    {
        $this->proceedsBase->addShortSale($change, $tradeProceeds);

        return $this;
    }

    // public function decreaseHolding(TradeQuantity $tradeQuantity, CostAmount $tradeCost): DecreasedHolding
    // {
    //     // $baseQuantity = $this->proceedsBase->getQuantity();

    //     $this->proceedsBase->addShortCover($tradeQuantity, $tradeCost);

    //     // $rgBasis = RealizedGainBasis::create(
    //     //     $this->securityNumber,
    //     //     $baseQuantity,
    //     //     $tradeQuantity,
    //     //     $this->proceedsBase->getTotalCost(),
    //     //     $this->proceedsBase->getTotalProceeds()
    //     // );

    //     return new DecreasedHolding ($this, $rgBasis);


    // }

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
