<?php

namespace App\Application\Procedures\ProcessTradeConfirmation\Projections;

use App\Application\Contracts\{
    TradeRepositoryContract,
};

use App\Application\Procedures\ProcessTradeConfirmation\{
    Events\TradeConfirmationCreated,
};

final class RecordTradeFromConfirmation
{
    public function __construct(
        private readonly TradeRepositoryContract $repository,
    ) {}

    public function handle(TradeConfirmationCreated $event): void
    {
        $confirmation = $event->confirmation;

        $this->repository->insert($confirmation);
    }
}
