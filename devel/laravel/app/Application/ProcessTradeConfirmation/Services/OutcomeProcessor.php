<?php

namespace App\Application\ProcessTradeConfirmation\Services;

use App\Application\ProcessTradeConfirmation\{
    Summary\OutcomeSummary,
};

use App\Application\ProcessTradeConfirmation\{
    Services\RegistrationService,
};

use App\Shared\Result;

final class OutcomeProcessor
{
    public function __construct(
        private readonly RegistrationService $registration,
    ) {}

    /** @return Result<OutcomeSummary> */
    public function process(OutcomeSummary $summary): Result
    {
        $confirmationOutcome = $summary->confirmationOutcome;
        $securityOutcome = $summary->securityOutcome;
        $positionOutcome = $summary->positionOutcome;
        $realizedGainOutcome = $positionOutcome->getRealizedGainOutcome();

        // Step 1: Register results
        $this->registration
            ->registerConfirmation($confirmationOutcome)
            ->registerPosition($positionOutcome)
            ->registerSecurity($securityOutcome)
            ->registerRealizedGainBasis($realizedGainOutcome);

        // Step 2: Persist all registrations
        $this->registration->persist();

        // Step 4: Summarize and return
        return Result::success(
            new OutcomeSummary(
                $confirmationOutcome,
                $securityOutcome
            )
        );
    }
}
