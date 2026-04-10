<?php

namespace App\Application\ProcessTradeConfirmation\Events;

use App\Domain\RealizedGain\Model\RealizedGainBasis;

final class RealizedGainCreated
{
    public function __construct(
        public readonly RealizedGainBasis $basis,
    ) {}
}