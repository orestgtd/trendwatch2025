<?php

namespace App\Domain\Expiration\Outcome;

use App\Domain\{
    Outcome\Persistence\PersistenceIntent,
    Position\Model\Position,
    Position\ValueObjects\PositionQuantity,
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
            PersistenceIntent::update(['position_quantity'])
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
