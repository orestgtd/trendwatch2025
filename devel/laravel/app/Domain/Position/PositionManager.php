<?php

namespace App\Domain\Position;

use App\Domain\Confirmation\{
    Model\Confirmation,
};

use App\Domain\Position\{
    Behaviour\BuyToDecrease,
    Behaviour\BuyToIncrease,
    Behaviour\CreateLongPosition,
    Behaviour\CreateShortPosition,
    Behaviour\SellToDecrease,
    Model\Position,
    Outcome\PositionOutcome,
};
use App\Domain\Position\Behaviour\SellToIncrease;
use App\Shared\Result;

final class PositionManager
{
    public function __construct(
        private BuyToDecrease $buyToDecrease,
        private BuyToIncrease $buyToIncrease,
        private CreateLongPosition $createLong,
        private CreateShortPosition $createShort,
        private SellToDecrease $sellToDecrease,
        private SellToIncrease $sellToIncrease,
    ) {}

    /** @return Result<PositionOutcome> */
    public function createPosition(Confirmation $confirmation): Result
    {
        return $confirmation->matchTradeAction(
            onBuy: fn () => $this->createLong->do($confirmation),
            onSell: fn () => $this->createShort->do($confirmation),
        );
    }

    /** @return Result<PositionOutcome> */
    public function increasePosition(
        Confirmation $confirmation,
        Position $position
    ): Result {
        return $confirmation->matchTradeAction(
            onBuy: fn () => $this->buyToIncrease->do($confirmation, $position),
            onSell: fn () => $this->sellToIncrease->do($confirmation, $position),
        );
    }

    /** @return Result<PositionOutcome> */
    public function decreasePosition(Confirmation $confirmation, Position $position): Result
    {
        return $confirmation->matchTradeAction(
            onBuy: fn () => $this->buyToDecrease->do($confirmation, $position),
            onSell: fn () => $this->sellToDecrease->do($confirmation, $position),
        );
    }
}
