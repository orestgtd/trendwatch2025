<?php

namespace App\Application\GetActivePositions\Queries;

use App\Domain\Position\{
    Builders\BuildPositionFromPersisted,
    Model\Position,
};

use App\Infrastructure\Laravel\Eloquent\Position\{
    Dto\PersistedPositionDto,
    Repositories\EloquentPositionRepository,
};

final class GetActivePositionsQuery
{
    public function __construct(
        private readonly EloquentPositionRepository $repository
    ) {}

    /** @return Position[] */
    public function all(): array
    {
        $persistedPositions = $this->repository->active();

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
