<?php

namespace App\Domain\Position\Behaviour;

use App\Domain\Confirmation\{
    Model\Confirmation,
};

use App\Domain\Position\{
    Model\LongPosition,
    Model\Position,
    Outcome\IncreasedHolding,
    Outcome\NewPositionCreated,
    Outcome\PositionOutcome,
    ValueObjects\PositionQuantity,
};

use App\Shared\Result;

final class BuyToOpenNewOrExistingPosition
{
    /** @return Result<PositionOutcome> */
    public function do(Confirmation $confirmation, ?Position $lookupPosition): Result
    {
        return $lookupPosition
            ? $this->buyToOpenExistingPosition($confirmation, $lookupPosition)
            : $this->buyToOpenNewLongPosition($confirmation);
    }

    /** @return Result<NewPositionCreated> */
    private function buyToOpenNewLongPosition(Confirmation $confirmation): Result
    {
        return Result::success(new NewPositionCreated(
            LongPosition::create(
                $confirmation->getSecurityInfo(),
                PositionQuantity::fromTradeQuantity($confirmation->getTradeQuantity()),
                $confirmation->netCost(),
            )
        ));
    }

    /** @return Result<IncreasedHolding> */
    private function buyToOpenExistingPosition(Confirmation $confirmation, Position $position): Result
    {
        $tradeNumber = $confirmation->getTradeNumber();

        return $position->getPositionType()->isLong()
            ? $this->buyToOpenExistingLongPosition($confirmation, $position)
            : Result::failure("Trade {$tradeNumber}: Cannot buy to open on a short position.");

    }

    /** @return Result<IncreasedHolding> */
    private function buyToOpenExistingLongPosition(Confirmation $confirmation, LongPosition $position): Result
    {
        return Result::success(new IncreasedHolding(
            $position->addPurchase(
                $confirmation->getTradeQuantity(),
                $confirmation->netCost()
            )
        ));
    }
}
