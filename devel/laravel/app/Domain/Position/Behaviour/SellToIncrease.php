<?php

namespace App\Domain\Position\Behaviour;

use App\Domain\{
    Confirmation\Model\Confirmation,
    Position\Model\Position,
    Position\Model\ShortPosition,
    Position\Outcome\IncreasedHolding,
};

use App\Foundation\Result;

final class SellToIncrease
{
    /** @return Result<IncreasedHolding> */
    public function do(Confirmation $confirmation, Position $position): Result
    {
        return $position->matchPositionType(
            onShort: fn () => $this->sellToIncreaseShortPosition($confirmation, $position),
            onLong: fn () => $this->sellToIncreaseLongPosition($confirmation)
        );
    }

    /** @return Result<IncreasedHolding> */
    private function sellToIncreaseShortPosition(Confirmation $confirmation, ShortPosition $position): Result
    {
        return Result::success(
            new IncreasedHolding(
                $position->addShortSale(
                    $confirmation->getTradeQuantity(),
                    $confirmation->netProceeds()
                )
            )
        );
    }

    /** @return Result<IncreasedHolding> */
    private function sellToIncreaseLongPosition(Confirmation $confirmation): Result
    {
        $tradeNumber = $confirmation->getTradeNumber();
        return Result::failure(
            "Trade {$tradeNumber}: Cannot sell to increase a long position."
        );
    }
}
