<?php

namespace App\Infrastructure\Laravel\Eloquent\Trade\Repositories;

use App\Domain\Confirmation\{
    Model\Confirmation,
    ValueObjects\TradeNumber,
};

use App\Infrastructure\Laravel\Eloquent\Trade\{
    Dto\PersistedTradeDto,
    Model\Trade as EloquentTrade,
};

use App\Infrastructure\Laravel\Contracts\TradeRepository;

final class EloquentTradeRepository implements TradeRepository
{
    public function findByTradeNumber(TradeNumber $tradeNumber): ?PersistedTradeDto
    {
        $eloquent = EloquentTrade::where('trade_number', (string) $tradeNumber)->first();

        return $eloquent
            ? new PersistedTradeDto(
                $eloquent->security_number,
                $eloquent->trade_number,
                $eloquent->trade_action,
                $eloquent->position_effect,
                $eloquent->trade_quantity,
                $eloquent->unit_price,
                $eloquent->commission,
                $eloquent->us_tax,
            )
            : $eloquent;
    }

    public function save(Confirmation $confirmation): void
    {
        $this
            ->toEloquent($confirmation)
            ->save();
    }

    private function toEloquent(Confirmation $confirmation): EloquentTrade
    {
        $eloquent = new EloquentTrade();

        $eloquent->security_number = $confirmation->getSecurityNumber();
        $eloquent->trade_number = $confirmation->getTradeNumber();
        $eloquent->trade_action = $confirmation->getTradeAction();
        $eloquent->position_effect = $confirmation->getPositionEffect();
        $eloquent->trade_quantity = $confirmation->getTradeQuantity();
        $eloquent->unit_price = $confirmation->getUnitPrice();
        $eloquent->commission = $confirmation->getCommission();
        $eloquent->us_tax = $confirmation->getUsTax();

        return $eloquent;
    }
}
