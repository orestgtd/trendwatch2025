<?php

namespace App\Application\ProcessTradeConfirmation;

use App\Application\ProcessTradeConfirmation\{
    Services\TradeServiceCoordinator,
    Summary\OutcomeSummary,
};

use App\Application\TradeConfirmation\{
    Dto\ParsedTradeData,
    TradeRequestParser,
};
use App\Domain\Position\Outcome\PositionOutcome;
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
        // Step 1: Parse and process request
        $resultOutcomes = $this->parser->parse($request)
        ->bind(
            fn (ParsedTradeData $parsed) => $this->processRequest($parsed)
        )->bind(
            fn (OutcomeSummary $summary) => $this->computePositionOutcome($summary)
        );

        // dd($resultOutcomes);

        if ($resultOutcomes->isFailure()) {
            return Result::failure($resultOutcomes->getError());
        }

        $outcomeSummary = $resultOutcomes->getValue();
        $confirmationOutcome = $outcomeSummary->confirmationOutcome;
        $securityOutcome = $outcomeSummary->securityOutcome;
        $positionOutcome = $outcomeSummary->positionOutcome;

        // Step 2: Register results
        $this->coordinator
            ->registerConfirmation($confirmationOutcome)
            ->registerPosition($positionOutcome)
            ->registerSecurity($securityOutcome);


        // Step 3: Persist all registrations
        $this->coordinator->persist();

        // Step 4: Summarize and return
        return Result::success(
            new OutcomeSummary(
                $confirmationOutcome,
                $securityOutcome
            )
        );
    }

    /** @return Result<OutcomeSummary> */
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
            new OutcomeSummary(
                $resultConfirmationOutcome->getValue(),
                $resultSecurityOutcome->getValue()
            )
        );
    }

    /** @return Result<OutcomeSummary> */
    private function computePositionOutcome(OutcomeSummary $summary): Result
    {
        return $this->coordinator->computePositionOutcome(
            $summary->getConfirmation()
        )->map(
            fn (PositionOutcome $outcome) => $summary->withPositionOutcome($outcome)
        );
    }
}
