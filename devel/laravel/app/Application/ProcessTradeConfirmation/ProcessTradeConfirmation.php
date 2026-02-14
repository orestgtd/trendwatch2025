<?php

namespace App\Application\ProcessTradeConfirmation;

use App\Application\ProcessTradeConfirmation\{
    Services\OutcomeProcessor,
    Services\TradeServiceCoordinator,
    Outcomes\TradeRequestOutcomes,
    Outcomes\TradeProcessingOutcomes,
};

use App\Application\TradeConfirmation\{
    Dto\ParsedTradeData,
    TradeRequestParser,
};

use App\Domain\Position\Outcome\PositionOutcome;

use App\Foundation\Result;

final class ProcessTradeConfirmation
{
    public function __construct(
        private OutcomeProcessor $outcomeProcessor,
        private TradeServiceCoordinator $coordinator,
        private TradeRequestParser $parser,
    ) {}

    /** @return Result<TradeProcessingOutcomes> */
    public function handle(array $request): Result
    {
        // Step 1: Parse and process request
        $resultOutcomes = $this->parser->parse($request)
        ->bind(
            fn (ParsedTradeData $parsed) => $this->processRequest($parsed)
        )->bind(
            fn (TradeRequestOutcomes $outcomes) => $this->computeProcessingOutcome($outcomes)
        );

        if ($resultOutcomes->isFailure()) {
            return Result::failure($resultOutcomes->getError());
        }

        $outcomeSummary = $resultOutcomes->getValue();

        return $this->outcomeProcessor->process($outcomeSummary);
    }

    /** @return Result<TradeRequestOutcomes> */
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
            new TradeRequestOutcomes(
                $resultConfirmationOutcome->getValue(),
                $resultSecurityOutcome->getValue()
            )
        );
    }

    /** @return Result<TradeProcessingOutcomes> */
    private function computeProcessingOutcome(TradeRequestOutcomes $requestOutcomes): Result
    {
        return $this->coordinator->computePositionOutcome(
            $requestOutcomes->getConfirmation(),
        )->map(
            fn (PositionOutcome $po) => new TradeProcessingOutcomes($requestOutcomes, $po)
        );
    }
}
