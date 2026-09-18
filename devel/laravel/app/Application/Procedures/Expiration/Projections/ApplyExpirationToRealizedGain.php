<?php

namespace App\Application\Procedures\Expiration\Projections;

use App\Application\{
    Contracts\RealizedGainRepositoryContract,
    Procedures\Expiration\Events\PositionExpired,
};

final class ApplyExpirationToRealizedGain
{
    public function __construct(
        private readonly RealizedGainRepositoryContract $realized_gain_repository,
    ) {}

    public function handle(PositionExpired $event): void
    {
        $realizedGainBasis = $event
            ->outcome
            ->getRealizedGainOutcome()
            ->getRealizedGainBasis();
        
        $this->realized_gain_repository->insert($realizedGainBasis);
    }
}
