<?php

namespace App\Infrastructure\Laravel\Eloquent\RealizedGain\Repositories;

use App\Application\{
    Contracts\RealizedGainRepositoryContract,
};

use App\Domain\{
    RealizedGain\Model\RealizedGainBasis,
    RealizedGain\Record\RealizedGainBasisRecord,
};

use App\Infrastructure\Laravel\Eloquent\RealizedGain\{
    Model\RealizedGainBasis as EloquentRealizedGainBasis,
};

class EloquentRealizedGainRepository implements RealizedGainRepositoryContract
{
    /** @return RealizedGainBasisRecord[] */
    public function all(): array
    {
        return EloquentRealizedGainBasis::all()
            ->map(fn(EloquentRealizedGainBasis $eloquent) => new RealizedGainBasisRecord(
                $eloquent->security_number,
                $eloquent->realization_date,
                $eloquent->position_type,
                $eloquent->realization_source,
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
        $eloquent->realization_date = $realizedGainBasis->getRealizationDate();
        $eloquent->position_type = $realizedGainBasis->getPositionType();
        $eloquent->realization_source = $realizedGainBasis->getRealizationSource();
        $eloquent->base_quantity = $realizedGainBasis->getBaseQuantity();
        $eloquent->trade_quantity = $realizedGainBasis->getTradeQuantity();
        $eloquent->unit_type = $realizedGainBasis->getUnitType();
        $eloquent->cost = $realizedGainBasis->getCost();
        $eloquent->proceeds = $realizedGainBasis->getProceeds();

        return $eloquent;
    }
}
