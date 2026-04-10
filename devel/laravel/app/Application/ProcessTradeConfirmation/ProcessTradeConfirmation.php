<?php

namespace App\Application\ProcessTradeConfirmation;

use App\Application\{
    Contracts\EventPersistenceContract,
    ProcessTradeConfirmation\Events\TradeConfirmationCreated,
    ProcessTradeConfirmation\Outcomes\TradeProcessingOutcomes,
    TradeConfirmation\Dto\ParsedTradeData,
    TradeConfirmation\TradeWorkflow,
};

use App\Domain\{
    Confirmation\Outcome\ConfirmationOutcome,
};

use App\Foundation\Result;

final class ProcessTradeConfirmation
{
    public function __construct(
         private readonly TradeWorkflow $workflow,
         private readonly EventPersistenceContract $eventPersistence,
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

        $this->eventPersistence->insert($event);
        event($event);
    }
}
