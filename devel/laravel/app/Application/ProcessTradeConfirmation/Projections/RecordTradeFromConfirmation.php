<?php

namespace App\Application\ProcessTradeConfirmation\Projections;

use App\Application\Contracts\{
    TradeRepositoryContract,
};

use App\Application\ProcessTradeConfirmation\{
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
