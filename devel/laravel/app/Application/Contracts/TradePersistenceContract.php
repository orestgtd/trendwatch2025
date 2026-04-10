<?php

namespace App\Application\Contracts;

use App\Application\ProcessTradeConfirmation\{
    Outcomes\TradeProcessingOutcomes,
};

interface TradePersistenceContract
{
    public function persist(TradeProcessingOutcomes $outcomes): void;
}
