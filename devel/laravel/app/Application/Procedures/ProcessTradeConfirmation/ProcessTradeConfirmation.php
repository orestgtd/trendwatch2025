<?php

namespace App\Application\Procedures\ProcessTradeConfirmation;

use App\Application\{
    EventManager,
    Procedures\ProcessTradeConfirmation\Events\TradeConfirmationCreated,
    Procedures\ProcessTradeConfirmation\Outcomes\TradeProcessingOutcomes,
    Procedures\ProcessTradeConfirmation\Dto\ParsedTradeData,
};

use App\Domain\{
    Confirmation\Outcome\ConfirmationOutcome,
};

use App\Foundation\Result;

final class ProcessTradeConfirmation
{
    public function __construct(
        private readonly TradeWorkflow $workflow,
        private readonly EventManager $eventManager,
    ) {}

    /** @return Result<TradeProcessingOutcomes> */
    public function handle(ParsedTradeData $parsed): Result
    {
        return $this->workflow->process($parsed)
            ->onSuccess(fn (TradeProcessingOutcomes $outcomes) => $this->tapTradeConfirmation($outcomes->getConfirmationOutcome()));
    }
 
    private function tapTradeConfirmation(ConfirmationOutcome $outcome): void
    {
        $confirmation = $outcome->getConfirmation();
        $event = new TradeConfirmationCreated($confirmation);

        $this->eventManager->record($event);
    }
}
