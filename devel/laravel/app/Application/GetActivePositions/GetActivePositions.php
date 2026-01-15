<?php

namespace App\Application\GetActivePositions;

use App\Application\GetActivePositions\{
    Queries\GetActivePositionsQuery,
};

use App\Shared\Result;

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
