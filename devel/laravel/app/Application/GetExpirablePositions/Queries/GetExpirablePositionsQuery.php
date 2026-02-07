<?php

namespace App\Application\GetExpirablePositions\Queries;

use App\Shared\Date;

use App\Domain\Position\{
    Builders\BuildPositionFromRecord,
    Model\Position,
    Record\PositionRecord,
};

use App\Infrastructure\Laravel\Eloquent\Position\{
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
            fn(PositionRecord $persisted) => BuildPositionFromRecord::from($persisted),
            $persistedPositions
        );
    }
}
