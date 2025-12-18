<?php

namespace App\Infrastructure\Laravel\Eloquent\RealizedGain\Repositories;

use App\Domain\RealizedGain\Model\RealizedGainBasis;

use App\Infrastructure\Laravel\Eloquent\{
    RealizedGain\Model\RealizedGainBasis as EloquentRealizedGainBasis
};

class EloquentRealizedGainRepository
{
    public function insert(RealizedGainBasis $realizedGainBasis): void
    {
        $this
            ->toEloquent($realizedGainBasis)
            ->save();
    }

    private function toEloquent(RealizedGainBasis $realizedGainBasis): EloquentRealizedGainBasis
    {
        $eloquent = new EloquentRealizedGainBasis();

        $eloquent->security_number = $realizedGainBasis->getSecurityNumber();
        $eloquent->trade_number = $realizedGainBasis->getTradeNumber();
        $eloquent->base_quantity = $realizedGainBasis->getBaseQuantity();
        $eloquent->trade_quantity = $realizedGainBasis->getTradeQuantity();
        $eloquent->unit_type = $realizedGainBasis->getUnitType();
        $eloquent->cost = $realizedGainBasis->getCost();
        $eloquent->proceeds = $realizedGainBasis->getProceeds();

        return $eloquent;
    }
}
