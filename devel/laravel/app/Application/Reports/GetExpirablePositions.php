<?php

namespace App\Application\Reports;

use App\Application\{
    Reports\Queries\GetExpirablePositionsQuery,
};

use App\Foundation\{
    Date,
    Result,
};

class GetExpirablePositions
{
    public function __construct(
        private readonly GetExpirablePositionsQuery $query
    ) {}

    /** @return Result<array> */
    public function handle(Date $asOf): Result
    {
        return Result::success(
            $this->query->asOf($asOf)
        );
    }
}
