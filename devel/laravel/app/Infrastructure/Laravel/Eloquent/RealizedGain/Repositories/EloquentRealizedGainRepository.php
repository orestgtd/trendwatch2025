<?php

namespace App\Infrastructure\Laravel\Eloquent\RealizedGain\Repositories;

use App\Domain\RealizedGain\Model\RealizedGainBasis;

use App\Infrastructure\Laravel\Eloquent\RealizedGain\{
    Model\RealizedGainBasis as EloquentRealizedGainBasis,
    Dto\PersistedRealizedGainBasisDto,
};

class EloquentRealizedGainRepository
{
    public function all(): array
    {
        return EloquentRealizedGainBasis::all()
            ->map(fn(EloquentRealizedGainBasis $eloquent) => new PersistedRealizedGainBasisDto(
                $eloquent->security_number,
                $eloquent->trade_number,
                $eloquent->base_quantity,
                $eloquent->trade_quantity,
                $eloquent->unit_type,
                $eloquent->cost,
                $eloquent->proceeds,
            ))
            ->all();
    }

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
