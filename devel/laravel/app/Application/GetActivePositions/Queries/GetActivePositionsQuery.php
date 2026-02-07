<?php

namespace App\Application\GetActivePositions\Queries;

use App\Domain\Position\{
    Builders\BuildPositionFromRecord,
    Model\Position,
    Record\PositionRecord,
};

use App\Infrastructure\Laravel\Eloquent\Position\{
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
            fn(PositionRecord $record) => BuildPositionFromRecord::from($record),
            $persistedPositions
        );
    }
}
