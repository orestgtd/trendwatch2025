<?php

namespace App\Domain\Position\Model;

use App\Domain\RealizedGain\{
    Model\RealizedGainBasis,
    ValueObjects\RealizationSource,
};

use App\Domain\Position\{
    Outcome\PositionExpired,
};

use App\Shared\Date;

final class ExpirablePosition
{
    public function __construct(
        private readonly AbstractPosition $position,
        private readonly Date $expiredAsOf
    ) {
        if (! $position->isExpiredAsOf($expiredAsOf)) {
            throw new \LogicException('Position is not expired.');
        }
    }

    public function expire(): PositionExpired
    {
        $realizedGainBasis = RealizedGainBasis::create(
            $this->position->getSecurityNumber(),
            RealizationSource::expiration($this->expiredAsOf),
            $this->position->getBaseQuantity(),
            $this->position->getPositionQuantity()->toTradeQuantity(),
            $this->position->getUnitType(),
            $this->position->getTotalCost(),
            $this->position->getTotalProceeds()
        );

        $this->position->expireQuantity();

        return PositionExpired::create($this->position, $realizedGainBasis);
    }

    public function position(): AbstractPosition
    {
        return $this->position;
    }

    public function asOf(): Date
    {
        return $this->expiredAsOf;
    }
}
