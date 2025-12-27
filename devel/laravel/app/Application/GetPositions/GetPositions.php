<?php

namespace App\Application\GetPositions;

use App\Application\GetPositions\{
    Queries\GetAllPositionsQuery,
};

use App\Shared\Result;

final class GetPositions
{
    public function __construct(
        private readonly GetAllPositionsQuery $query
    ) {}

    /** @return Result<array> */
    public function handle(): Result
    {
        return Result::success(
            $this->query->all()
        );
    }
}
