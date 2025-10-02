<?php

namespace App\Application\Services;

use App\Domain\{
    Confirmation\Outcome\ConfirmationOutcome,
    Position\Outcome\PositionOutcomePlaceholder,
};
use App\Domain\Confirmation\Model\Confirmation;
use App\Domain\Security\ValueObjects\SecurityNumber;
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
        $x = $this->processConfirmation($confirmationOutcome->getConfirmation());

        dd($x);

        return Result::success(
            new PositionOutcomePlaceholder()
        );
    }

    private function processConfirmation(Confirmation $confirmation): void
    {
        $lookup = $this->lookupPosition($confirmation->getSecurityNumber());

        dd($lookup);
    }

    private function lookupPosition(SecurityNumber $securityNumber): void
    {
        dd($securityNumber);
    }
}
