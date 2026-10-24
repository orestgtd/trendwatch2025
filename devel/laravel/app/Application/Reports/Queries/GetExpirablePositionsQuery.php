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

use App\Foundation\Date;

class GetExpirablePositionsQuery
{
    public function __construct(
        private readonly PositionRepositoryContract $repository
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
