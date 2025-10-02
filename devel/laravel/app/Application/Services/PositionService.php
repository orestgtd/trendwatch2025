<?php

namespace App\Application\Services;

use App\Domain\{
    Confirmation\Outcome\ConfirmationOutcome,
    Position\Outcome\PositionOutcomePlaceholder,
};

use App\Shared\Result;

final class PositionService
{
    /**
     * Update the position based on the confirmation outcome.
     *
     * Currently a placeholder: does nothing but returns a PositionOutcome.
     *
     * @param ConfirmationOutcome $confirmationOutcome
     * @return Result<PositionOutcomePlaceholder>
     */
    public function updatePosition(ConfirmationOutcome $confirmationOutcome): Result
    {
        return Result::success(
            new PositionOutcomePlaceholder()
        );
    }
}
