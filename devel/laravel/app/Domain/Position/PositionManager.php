<?php

namespace App\Domain\Position;

use App\Domain\Confirmation\{
    Model\Confirmation,
};

use App\Domain\Position\{
    Behaviour\AddToNewOrExistingPosition,
    Behaviour\ReduceExistingPosition,
    Model\Position,
    Outcome\PositionOutcome,
};

use App\Shared\Result;

final class PositionManager
{
    public function __construct(
        private AddToNewOrExistingPosition $addToNewOrExistingPosition,
        private ReduceExistingPosition $reduceExistingPosition,
    ) {}

    /**
     * Update the position based on the confirmation outcome.
     *
     * @return Result<PositionOutcome>
     */
    public function createOrUpdatePosition(Confirmation $confirmation, ?Position $lookupPosition): Result
    {
        return Result::success(
            $confirmation->matchPositionEffect(
                onOpen: fn(Confirmation $confirmation) => $this->addToNewOrExistingPosition->execute($confirmation, $lookupPosition),
                onClose: fn(Confirmation $confirmation) => $this->reduceExistingPosition->reduceExistingPosition($confirmation, $lookupPosition),
            )
        );
    }
}
