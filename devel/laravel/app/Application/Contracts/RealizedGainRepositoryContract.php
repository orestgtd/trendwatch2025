<?php

namespace App\Application\Contracts;

use App\Domain\RealizedGain\{
    Model\RealizedGainBasis,
    Record\RealizedGainBasisRecord,
};

interface RealizedGainRepositoryContract
{
    // Commands

    public function insert(RealizedGainBasis $basis): void;

    // Queries

    /** @return RealizedGainBasisRecord[] */
    public function all(): array;
}
