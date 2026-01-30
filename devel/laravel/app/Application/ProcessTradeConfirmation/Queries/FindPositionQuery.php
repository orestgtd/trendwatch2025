<?php

namespace App\Application\ProcessTradeConfirmation\Queries;

use App\Domain\Kernel\Identifiers\{
    SecurityNumber,
};

use App\Domain\Position\{
    Builders\BuildPositionFromPersisted,
    Model\Position,
};

use App\Domain\Security\{
    ValueObjects\SecurityInfo,
};

use App\Infrastructure\Laravel\Eloquent\Position\{
    Dto\PersistedPositionDto,
    Repositories\EloquentPositionRepository as PositionRepository,
};

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
            $persisted->securityInfo,
            $persisted->positionType,
            $persisted->positionQuantity,
            $persisted->totalCost,
            $persisted->totalProceeds,
        );
    }
}
