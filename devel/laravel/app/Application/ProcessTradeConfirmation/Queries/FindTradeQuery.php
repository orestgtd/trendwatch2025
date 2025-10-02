<?php

namespace App\Application\ProcessTradeConfirmation\Queries;

use App\Domain\Confirmation\{
    Builders\BuildNewConfirmation,
    Model\Confirmation,
    ValueObjects\TradeNumber,
};

use App\Infrastructure\Laravel\Eloquent\Trade\{
    Dto\PersistedTradeDto,
    Repositories\EloquentTradeRepository as TradeRepository,
};

final class FindTradeQuery
{
    public function __construct(
        private readonly TradeRepository $repository
    ) {}

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
            $persisted->tradeAction,
            $persisted->positionEffect,
            $persisted->tradeQuantity,
            // $persisted->tradeUnitType,
            $persisted->unitPrice,
            $persisted->commission,
            $persisted->usTax,
        );
    }
}
