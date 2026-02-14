<?php

namespace App\Application\ProcessTradeConfirmation\Services;

use App\Application\ProcessTradeConfirmation\{
    Outcomes\TradeProcessingOutcomes,
    Services\RegistrationService,
};

use App\Foundation\Result;

final class OutcomeProcessor
{
    public function __construct(
        private readonly RegistrationService $registration,
    ) {}

    /** @return Result<TradeProcessingOutcomes> */
    public function process(TradeProcessingOutcomes $outcomes): Result
    {
        $confirmationOutcome = $outcomes->getConfirmationOutcome();
        $securityOutcome = $outcomes->getSecurityOutcome();
        $positionOutcome = $outcomes->getPositionOutcome();
        $realizedGainOutcome = $positionOutcome->getRealizedGainOutcome();

        $this->registration
            ->registerConfirmation($confirmationOutcome)
            ->registerPosition($positionOutcome)
            ->registerSecurity($securityOutcome)
            ->registerRealizedGainBasis($realizedGainOutcome)
            ->persist();

        // Step 4: Summarize and return
        return Result::success($outcomes);
    }
}
