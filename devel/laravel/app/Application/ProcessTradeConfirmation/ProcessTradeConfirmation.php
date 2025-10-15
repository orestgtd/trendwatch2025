<?php

namespace App\Application\ProcessTradeConfirmation;

use App\Application\ProcessTradeConfirmation\Services\TradeServiceCoordinator;

use App\Application\ProcessTradeConfirmation\{
    Dto\ParsedSecurityRequestDto,
    Dto\ParsedTradeRequestDto,
};

use App\Application\ProcessTradeConfirmation\Summary\{
    OutcomeSummary,
};

use App\Domain\{
    Confirmation\Outcome\ConfirmationOutcome,
    Position\Outcome\PositionOutcome,
    Security\Outcome\SecurityOutcome,
};
use App\Shared\Result;

final class ProcessTradeConfirmation
{
    public function __construct(
        private TradeServiceCoordinator $coordinator,
    ) {}

    /** @return Result<OutcomeSummary> */
    public function handle(array $request): Result
    {
        // Step 1: Parse and process trade & security outcomes
        $parseResult = $this->processParsedOutcomes($request);
        if ($parseResult->isFailure()) {
            return $parseResult;
        }
        [$resultConfirmationOutcome, $resultSecurityOutcome] = $parseResult->getValue();

        // Step 2: Evaluate and update position
        $resultPositionOutcome = $this->processPositionOutcome($resultConfirmationOutcome);

        // Step 3: Register results
        $this->registerOutcomes($resultConfirmationOutcome, $resultSecurityOutcome, $resultPositionOutcome);

        // Step 4: Persist all registrations
        $this->coordinator->registrationService->persist();

        // Step 5: Summarize and return
        return Result::success(
            new OutcomeSummary(
                $resultConfirmationOutcome->getValue(),
                $resultSecurityOutcome->getValue()
            )
        );
    }

    /**
     * Parse and process trade + security outcomes from request.
     *
     * @return Result<array{Result<ConfirmationOutcome>, Result<SecurityOutcome>}>
     */
    private function processParsedOutcomes(array $request): Result
    {
        $resultConfirmationOutcome = $this->coordinator->tradeParser->parse($request)
            ->bind(fn(ParsedTradeRequestDto $parsed) => $this->coordinator->tradeService->processConfirmationRequest($parsed));

        if ($resultConfirmationOutcome->isFailure()) {
            return $resultConfirmationOutcome;
        }

        $resultSecurityOutcome = $this->coordinator->securityParser->parse($request)
            ->bind(fn(ParsedSecurityRequestDto $parsed) => $this->coordinator->securityService->processSecurityRequest($parsed));

        if ($resultSecurityOutcome->isFailure()) {
            return $resultSecurityOutcome;
        }

        return Result::success([$resultConfirmationOutcome, $resultSecurityOutcome]);
    }

    /**
     * Evaluate confirmation and create/update position.
     *
     * @param Result<ConfirmationOutcome> $resultConfirmationOutcome
     * @return Result<PositionOutcome>
     */
    private function processPositionOutcome(Result $resultConfirmationOutcome): Result
    {
        return $resultConfirmationOutcome
            ->bind(
                fn(ConfirmationOutcome $confirmationOutcome) =>
                $this->coordinator->positionService->createOrupdatePosition($confirmationOutcome->getConfirmation())
            )
            ->tap(
                fn(PositionOutcome $outcome) =>
                $this->coordinator->registrationService->registerPosition($outcome)
            );
    }

    /**
     * Register all successful outcomes.
     */
    private function registerOutcomes(
        Result $resultConfirmationOutcome,
        Result $resultSecurityOutcome,
        Result $resultPositionOutcome
    ): void {
        $resultConfirmationOutcome
            ->tap(
                fn(ConfirmationOutcome $outcome) =>
                $this->coordinator->registrationService->registerConfirmation($outcome)
            );

        $resultSecurityOutcome
            ->tap(
                fn(SecurityOutcome $outcome) =>
                $this->coordinator->registrationService->registerSecurity($outcome)
            );

        $resultPositionOutcome
            ->tap(
                fn(PositionOutcome $outcome) =>
                $this->coordinator->registrationService->registerPosition($outcome)
            );
    }
}
