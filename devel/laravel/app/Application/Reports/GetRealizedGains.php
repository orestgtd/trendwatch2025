<?php

namespace App\Application\Reports;

use App\Application\{
    Reports\Queries\GetAllRealizedGainsQuery,
};

use App\Domain\RealizedGain\{
    Record\RealizedGainBasisRecord,
};

use App\Foundation\Result;

class GetRealizedGains
{
    public function __construct(
        private readonly GetAllRealizedGainsQuery $query
    ) {}

    /** @return Result<RealizedGainBasisRecord[]> */
    public function handle(): Result
    {
        return Result::success(
            $this->query->all()
        );
    }
}
