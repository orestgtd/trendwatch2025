<?php

namespace App\Domain\Expiration\Outcome;

use App\Domain\{
    Position\Model\Position,
    RealizedGain\Model\RealizedGainBasis,
    RealizedGain\Outcome\NewRealizedGainCreated,
};

final class PositionExpired extends AbstractExpirationOutcome
{
    private function __construct(
        Position $position,
        private readonly RealizedGainBasis $realizedGainBasis,
    ) {
        parent::__construct(
            $position,
        );
    }

    public static function create(
        Position $position,
        RealizedGainBasis $realizedGainBasis,
    ): self {
        return new self($position, $realizedGainBasis);
    }

    public function getRealizedGainOutcome(): NewRealizedGainCreated
    {
        return NewRealizedGainCreated::create($this->realizedGainBasis);
    }
}
