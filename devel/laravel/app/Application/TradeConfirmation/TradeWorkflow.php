<?php

namespace App\Application\TradeConfirmation;

use App\Application\ProcessTradeConfirmation\{
    Outcomes\TradeProcessingOutcomes,
    Outcomes\TradeRequestOutcomes,
    Services\PositionProcessor,
    Services\SecurityService,
    Services\TradeService,
};

use App\Application\TradeConfirmation\{
    Dto\ParsedTradeData,
};

use App\Domain\{
    Position\Outcome\PositionOutcome,
};

use App\Foundation\Result;

final class TradeWorkflow
{
    public function __construct(
        private PositionProcessor $positionProcessor,
        private SecurityService $securityService,
        private TradeService $tradeService,
    ) {}

    /** @return Result<TradeProcessingOutcomes> */
    public function process(ParsedTradeData $parsed): Result
    {
        return $this->processRequest($parsed)
            ->bind(
                fn (TradeRequestOutcomes $outcomes) => $this->computeExecutionOutcome($outcomes)
            );
    }

    /** @return Result<TradeRequestOutcomes> */
    private function processRequest(ParsedTradeData $parsed): Result
    {
        $resultConfirmationOutcome = $this->tradeService->processConfirmationRequest($parsed->trade);
        if ($resultConfirmationOutcome->isFailure()) {
            return Result::failure($resultConfirmationOutcome->getError());
        }

        $resultSecurityOutcome = $this->securityService->processSecurityRequest($parsed->security);
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
    private function computeExecutionOutcome(TradeRequestOutcomes $requestOutcomes): Result
    {
        return $this->positionProcessor->computePositionOutcome(
            $requestOutcomes->getConfirmation(),
        )->map(
            fn (PositionOutcome $po) => new TradeProcessingOutcomes($requestOutcomes, $po)
        );
    }
}
