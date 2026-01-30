<?php

namespace App\Application\GetExpirablePositions\Queries;

use App\Shared\Date;

use App\Domain\Position\{
    Builders\BuildPositionFromPersisted,
    Model\Position,
};

use App\Domain\Security\{
    ValueObjects\SecurityInfo,
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
            $persisted->securityInfo,
            $persisted->positionType,
            $persisted->positionQuantity,
            $persisted->totalCost,
            $persisted->totalProceeds,
        );
    }
}
