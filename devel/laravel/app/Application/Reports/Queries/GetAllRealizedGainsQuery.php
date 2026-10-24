<?php

namespace App\Application\Reports\Queries;

use App\Application\{
    Contracts\RealizedGainRepositoryContract,
};

use App\Domain\RealizedGain\{
    Record\RealizedGainBasisRecord,
};

final class GetAllRealizedGainsQuery
{
    public function __construct(
        private readonly RealizedGainRepositoryContract $repository,
    ) {}

    /** @return RealizedGainBasisRecord[] */
    public function all(): array
    {
        return $this->repository->all();
    }
}
