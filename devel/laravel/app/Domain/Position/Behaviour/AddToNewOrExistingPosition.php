<?php

namespace App\Domain\Position\Behaviour;

use App\Domain\Confirmation\{
    Model\Confirmation,
};

use App\Domain\Position\{
    Model\Position,
    Outcome\PositionOutcome,
};

use App\Shared\Result;

final class AddToNewOrExistingPosition
{
    public function __construct(
        private BuyToOpenNewOrExistingPosition $buyToOpenNewOrExistingPosition,
        private SellToOpenNewOrExistingPosition $sellToOpenNewOrExistingPosition
    ) {}

    /** @return Result<PositionOutcome> */
    public function do(Confirmation $confirmation, ?Position $lookupPosition): Result
    {
        return
            $confirmation->matchTradeAction(
                onBuy: fn(Confirmation $confirmation) => $this->buyToOpenNewOrExistingPosition->do($confirmation, $lookupPosition),
                onSell: fn(Confirmation $confirmation) => $this->sellToOpenNewOrExistingPosition->do($confirmation, $lookupPosition),
            );
    }
}
