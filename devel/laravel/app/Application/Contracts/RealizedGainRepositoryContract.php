<?php

namespace App\Application\Contracts;

use App\Domain\RealizedGain\{
    Model\RealizedGainBasis,
};

use App\Infrastructure\{
    Laravel\Eloquent\RealizedGain\Dto\PersistedRealizedGainBasisDto,
};

interface RealizedGainRepositoryContract
{
    // Commands

    public function insert(RealizedGainBasis $basis): void;

    // Queries

    /** @return PersistedRealizedGainBasisDto[] */
    public function all(): array;
}
