<?php

namespace App\Domain\Position\Behaviour;

use App\Domain\{
    Confirmation\Model\Confirmation,
    Position\Model\LongPosition,
    Position\Model\Position,
    Position\Outcome\IncreasedHolding,
};

use App\Shared\Result;

final class BuyToIncrease
{
    /** @return Result<IncreasedHolding> */
    public function do(Confirmation $confirmation, Position $position): Result
    {
        return $position->matchPositionType(
            onLong: fn () => $this->buyToIncreaseLongPosition($confirmation, $position),
            onShort: fn () => $this->buyToIncreaseShortPosition($confirmation)
        );
    }

    /** @return Result<IncreasedHolding> */
    private function buyToIncreaseLongPosition(Confirmation $confirmation, LongPosition $position): Result
    {
        return Result::success(
            new IncreasedHolding(
                $position->addPurchase(
                    $confirmation->getTradeQuantity(),
                    $confirmation->netCost()
                )
            )
        );
    }

    /** @return Result<IncreasedHolding> */
    private function buyToIncreaseShortPosition(Confirmation $confirmation): Result
    {
        return Result::failure(
            "Trade {$confirmation->getTradeNumber()}: Cannot buy to increase a short position."
        );
    }
}
