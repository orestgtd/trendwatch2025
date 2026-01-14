<?php

namespace App\Application\GetPositions\Queries;

use App\Domain\Position\{
    Builders\BuildPositionFromPersisted,
    Model\Position,
};

use App\Domain\Security\ValueObjects\Symbol;

use App\Infrastructure\Laravel\Eloquent\Position\{
    Dto\PersistedPositionDto,
    Repositories\EloquentPositionRepository,
};

final class GetAllPositionsQuery
{
    public function __construct(
        private readonly EloquentPositionRepository $repository
    ) {}

    /** @return Position[] */
    public function all(): array
    {
        $persistedPositions = $this->repository->all();

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
            $persisted->totalProceeds
        );
    }
}
