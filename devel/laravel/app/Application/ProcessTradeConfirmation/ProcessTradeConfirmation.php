<?php

namespace App\Application\ProcessTradeConfirmation;

use App\Application\ProcessTradeConfirmation\Services\TradeServiceCoordinator;

use App\Application\ProcessTradeConfirmation\Summary\{
    OutcomeSummary,
};

use App\Application\TradeConfirmation\{
    Dto\ParsedTradeData,
    TradeProcessingOutcomes,
    TradeRequestParser,
};

use App\Domain\{
    Confirmation\Outcome\ConfirmationOutcome,
    Security\Outcome\SecurityOutcome,
};

use App\Shared\Result;

final class ProcessTradeConfirmation
{
    public function __construct(
        private TradeServiceCoordinator $coordinator,
        private TradeRequestParser $parser,
    ) {}

    /** @return Result<OutcomeSummary> */
    public function handle(array $request): Result
    {
        // Step 1: Parse request
        $processResult = $this->parser->parse($request)
        ->bind(
            fn (ParsedTradeData $parsed) => $this->processRequest($parsed)
        );
        if ($processResult->isFailure()) {
            return Result::failure($processResult->getError());
        }

        $processingOutcomes = $processResult->getValue();
        $confirmationOutcome = $processingOutcomes->confirmationOutcome;
        $securityOutcome = $processingOutcomes->securityOutcome;

        // Step 2: Evaluate and update position
        $resultPositionOutcome = $this->coordinator->computePositionOutcome($confirmationOutcome->getConfirmation());
        if ($resultPositionOutcome->isFailure()) {
            return Result::failure($resultPositionOutcome->getError());
        }

        // Step 3: Register results
        $this->coordinator
            ->registerConfirmation($confirmationOutcome)
            ->registerPosition($resultPositionOutcome->getValue())
            ->registerSecurity($securityOutcome);


        // Step 4: Persist all registrations
        $this->coordinator->persist();

        // Step 5: Summarize and return
        return Result::success(
            new OutcomeSummary(
                $confirmationOutcome,
                $securityOutcome
            )
        );
    }

    /** @return Result<TradeProcessingOutcomes> */
    private function processRequest(ParsedTradeData $parsed): Result
    {
        $resultConfirmationOutcome = $this->coordinator->processConfirmationRequest($parsed->trade);
        if ($resultConfirmationOutcome->isFailure()) {
            return Result::failure($resultConfirmationOutcome->getError());
        }

        $resultSecurityOutcome = $this->coordinator->processSecurityRequest($parsed->security);
        if ($resultSecurityOutcome->isFailure()) {
            return Result::failure($resultSecurityOutcome->getError());
        }

        return Result::success(
            new TradeProcessingOutcomes(
                $resultConfirmationOutcome->getValue(),
                $resultSecurityOutcome->getValue()
            )
        );
    }
}
