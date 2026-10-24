<?php

namespace App\Application\Reports\Queries;

use App\Application\{
    Contracts\PositionRepositoryContract,
};

use App\Domain\Position\{
    Builders\BuildPositionFromRecord,
    Model\Position,
    Record\PositionRecord,
};

final class GetActivePositionsQuery
{
    public function __construct(
        private readonly PositionRepositoryContract $repository
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
