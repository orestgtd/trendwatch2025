<?php

namespace App\Application\Contracts;

use App\Domain\{
    RealizedGain\Model\RealizedGainBasis,
};

// use App\Infrastructure\Laravel\Eloquent\RealizedGain\Model\RealizedGainRecord;

interface RealizedGainRepositoryContract
{
    // Commands
    public function insert(RealizedGainBasis $basis): void;

    // Queries
    // public function findByTradeNumber(TradeNumber $tradeNumber): ?RealizedGainRecord;
}
