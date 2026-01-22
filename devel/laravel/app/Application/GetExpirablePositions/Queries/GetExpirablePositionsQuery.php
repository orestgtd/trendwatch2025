<?php

namespace App\Application\GetExpirablePositions\Queries;

use App\Shared\Date;

use App\Domain\Position\{
    Builders\BuildPositionFromPersisted,
    Model\Position,
};

use App\Infrastructure\Laravel\Eloquent\Position\{
    Dto\PersistedPositionDto,
    Repositories\EloquentPositionRepository,
};

final class GetExpirablePositionsQuery
{
    public function __construct(
        private readonly EloquentPositionRepository $repository
    ) {}

    /** @return Position[] */
    public function asOf(Date $asOf): array
    {
        $persistedPositions = $this->repository->expiredAsOf($asOf);

        return array_map(
            fn(PersistedPositionDto $dto) => $this->buildPositionFromPersisted($dto),
            $persistedPositions
        );
    }

    private function buildPositionFromPersisted(PersistedPositionDto $persisted): Position
    {
        return BuildPositionFromPersisted::from(
            $persisted->securityNumber,
            $persisted->symbol,
            $persisted->positionType,
            $persisted->positionQuantity,
            $persisted->unitType,
            $persisted->totalCost,
            $persisted->totalProceeds,
            $persisted->expirationDate,
        );
    }
}
