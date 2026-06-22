<?php

namespace App\Application\Expiration\Projections;

use App\Application\{
    Contracts\PositionRepositoryContract,
    Expiration\Events\PositionExpired,
};

use App\Domain\{
    Position\ValueObjects\PositionQuantity,
};

final class ApplyExpirationToPosition
{
    public function __construct(
        private readonly PositionRepositoryContract $position_repository,
    ) {}

    public function handle(PositionExpired $event): void
    {
        $this->position_repository->updateQuantity(
            securityNumber: $event
                ->outcome
                ->getPosition()
                ->getSecurityNumber(),
            quantity: PositionQuantity::zero()
        );
    }
}
