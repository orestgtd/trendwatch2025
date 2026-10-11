<?php

namespace App\Domain\Expiration\Outcome;

use App\Domain\{
    Expiration\Outcome\AbstractExpirationOutcome,
    Position\Model\Position,
    RealizedGain\Outcome\NoRealizedGain,
};

final class PositionNotExpired extends AbstractExpirationOutcome
{
    public static function create(
        Position $position,
    ): self {
        return new self(
            $position,
        );
    }

    public function getRealizedGainOutcome(): NoRealizedGain
    {
        return NoRealizedGain::create();
    }
}
