<?php

namespace App\Application\GetRealizedGains;

use App\Application\GetRealizedGains\{
    Queries\GetAllRealizedGainsQuery,
};

use App\Foundation\Result;

final class GetRealizedGains
{
    public function __construct(
        private readonly GetAllRealizedGainsQuery $query
    ) {}

    /** @return Result<array> */
    public function handle(): Result
    {
        return Result::success(
            $this->query->all()
        );
    }
}
