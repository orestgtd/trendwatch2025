<?php

namespace App\Application\GetRealizedGains\Queries;

use App\Infrastructure\Laravel\Eloquent\RealizedGain\{
        Repositories\EloquentRealizedGainRepository,
};

use App\Domain\RealizedGain\{
    Model\RealizedGainBasis,
};

final class GetAllRealizedGainsQuery
{
    public function __construct(
        private readonly EloquentRealizedGainRepository $repository
    ) {}

    /** @return RealizedGainBasis[] */
    public function all(): array
    {
        return $this->repository->all();
    }
}
