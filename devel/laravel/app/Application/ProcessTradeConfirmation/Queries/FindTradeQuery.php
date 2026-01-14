<?php

namespace App\Application\ProcessTradeConfirmation\Queries;

use App\Domain\Kernel\Identifiers\{
    TradeNumber,
};

use App\Domain\Confirmation\{
    Builders\BuildNewConfirmation,
    Model\Confirmation,
};

use App\Domain\Security\{
    ValueObjects\Symbol,
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
            $persisted->symbol,
            $persisted->tradeNumber,
            $persisted->tradeAction,
            $persisted->positionEffect,
            $persisted->tradeQuantity,
            $persisted->unitType,
            $persisted->unitPrice,
            $persisted->commission,
            $persisted->usTax,
            $persisted->expirationDate,
        );
    }
}
