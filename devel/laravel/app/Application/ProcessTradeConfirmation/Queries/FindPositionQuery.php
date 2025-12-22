<?php

namespace App\Application\ProcessTradeConfirmation\Queries;

use App\Domain\Position\{
    Model\Position,
};

use App\Domain\Kernel\Identifiers\{
    SecurityNumber,
};

use App\Infrastructure\Laravel\Eloquent\Position\{
    Dto\PersistedPositionDto,
    Repositories\EloquentPositionRepository as PositionRepository,
};

use App\Domain\Position\Builders\BuildPositionFromPersisted;

final class FindPositionQuery
{
    public function __construct(
        private readonly PositionRepository $repository
        ){}

    public function findBySecurityNumber(SecurityNumber $securityNumber): ?Position
    {
        $persisted = $this->repository->findBySecurityNumber($securityNumber);

        return $persisted
        ? $this->buildPositionFromPersisted($persisted)
        : null;
    }

    private function buildPositionFromPersisted(PersistedPositionDto $persisted): Position
    {
        return BuildPositionFromPersisted::from(
            $persisted->securityNumber,
            $persisted->positionType,
            $persisted->positionQuantity,
            $persisted->unitType,
            $persisted->totalCost,
            $persisted->totalProceeds
        );
    }
}
