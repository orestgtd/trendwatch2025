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
        return $confirmation->matchPositionEffect(
            onOpen: fn(Confirmation $confirmation) => $this->openPosition($confirmation, $lookupPosition),
            onClose: fn(Confirmation $confirmation) => $this->closePosition($confirmation, $lookupPosition),
        );
    }

    /** @return Result<PositionOutcome> */
    private function openPosition(
        Confirmation $confirmation,
        ?Position $lookupPosition
    ): Result {
        return Result::success(
            $this->addToNewOrExistingPosition
                ->do($confirmation, $lookupPosition)
        );
    }

    /** @return Result<PositionOutcome> */
    private function closePosition(Confirmation $confirmation, ?Position $lookupPosition): Result
    {
        $failure = function (Confirmation $confirmation): string {
            $securityNumber = $confirmation->getSecurityNumber();
            $tradeNumber = $confirmation->getTradeNumber();
            return "Cannot reduce non-existent position for security {$securityNumber}, trade number {$tradeNumber}";
        };

        return is_null($lookupPosition)
        ? Result::failure($failure($confirmation))
        : Result::success(
            $this->reduceExistingPosition->do($confirmation, $lookupPosition)
        );
    }
}
