<?php

namespace App\Application\Trade\Queries;

use App\Domain\Confirmation\{
    Builders\BuildNewConfirmation,
    Model\Confirmation,
    ValueObjects\TradeNumber,
};

use App\Infrastructure\Laravel\Eloquent\Trade\{
    Dto\PersistedTradeDto,
    Repositories\EloquentTradeRepository as TradeRepository,
};

final class FindByTradeNumberQuery
{
    public function __construct(
        private readonly TradeRepository $repository
        ){}

    public function findByTradeNumber(TradeNumber $tradeNumber): ?Confirmation
    {
        $persisted = $this->repository->findByTradeNumber($tradeNumber);

        return $persisted
        ? $this->buildConfirmation($persisted)
        : $persisted;
    }

    private function buildConfirmation(PersistedTradeDto $persisted): Confirmation
    {
        return BuildNewConfirmation::from(
            $persisted->securityNumber,
            $persisted->tradeNumber,
        );
    }
}
