<?php

namespace App\Domain\Position\Behaviour;

use App\Domain\Confirmation\{
    Model\Confirmation,
};

use App\Domain\Position\{
    Model\ShortPosition,
    Model\Position,
    Outcome\IncreasedHolding,
    Outcome\NewPositionCreated,
    Outcome\PositionOutcome,
    ValueObjects\PositionQuantity,
};

use App\Shared\Result;

final class SellToOpenNewOrExistingPosition
{
    /** @return Result<PositionOutcome> */
    public function do(Confirmation $confirmation, ?Position $lookupPosition): Result
    {
        return $lookupPosition
            ? $this->sellToOpenExistingPosition($confirmation, $lookupPosition)
            : $this->sellToOpenNewShortPosition($confirmation);
    }

    /** @return Result<NewPositionCreated> */
    private function sellToOpenNewShortPosition(Confirmation $confirmation): Result
    {
        return Result::success(new NewPositionCreated(
            ShortPosition::create(
                $confirmation->getSecurityNumber(),
                $confirmation->getSymbol(),
                PositionQuantity::fromTradeQuantity($confirmation->getTradeQuantity()),
                $confirmation->getUnitType(),
                $confirmation->netProceeds(),
                $confirmation->getExpirationDate(),
            )
        ));
    }

    /** @return Result<IncreasedHolding> */
    private function sellToOpenExistingPosition(Confirmation $confirmation, Position $position): Result
    {
        $tradeNumber = $confirmation->getTradeNumber();

        return $position->getPositionType()->isShort()
            ? $this->sellToOpenExistingShortPosition($confirmation, $position)
            : Result::failure("Trade {$tradeNumber}: Cannot sell to open on a long position.");
    }

    /** @return Result<IncreasedHolding> */
    private function sellToOpenExistingShortPosition(Confirmation $confirmation, ShortPosition $position): Result
    {
        return Result::success(new IncreasedHolding(
            $position->addShortSale(
                $confirmation->getTradeQuantity(),
                $confirmation->netProceeds()
            )
        ));
    }
}
