<?php

namespace App\Application\Reports;

use App\Application\{
    Reports\Queries\GetActivePositionsQuery,
};

use App\Foundation\Result;

final class GetActivePositions
{
    public function __construct(
        private readonly GetActivePositionsQuery $query
    ) {}

    /** @return Result<array> */
    public function handle(): Result
    {
        return Result::success(
            $this->query->all()
        );
    }
}
