<?php

namespace App\Infrastructure\Laravel\Eloquent\Trade\Repositories;

use App\Domain\{
    Confirmation\Model\Confirmation,
    Confirmation\Record\TradeRecord,
    Kernel\Identifiers\TradeNumber,
    Security\Expiration\ExpirationRule,
    Security\ValueObjects\SecurityInfo,
};

use App\Infrastructure\Laravel\Eloquent\Trade\{
    Model\Trade as EloquentTrade,
};

class EloquentTradeRepository
{
    public function findByTradeNumber(TradeNumber $tradeNumber): ?TradeRecord
    {
        $eloquent = EloquentTrade::where('trade_number', (string) $tradeNumber)->first();

        return $eloquent
            ? new TradeRecord(
                SecurityInfo::from(
                    $eloquent->security_number,
                    $eloquent->symbol,
                    $eloquent->description,
                    $eloquent->unit_type,
                    ExpirationRule::fromNullableDate($eloquent->expiration_date)
                ),
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

    public function insert(Confirmation $confirmation): void
    {
        $this
            ->toEloquent($confirmation)
            ->save();
    }

    private function toEloquent(Confirmation $confirmation): EloquentTrade
    {
        $eloquent = new EloquentTrade();

        $eloquent->security_number = $confirmation->getSecurityNumber();
        $eloquent->symbol = $confirmation->getSymbol();
        $eloquent->description = $confirmation->getDescription();
        $eloquent->trade_number = $confirmation->getTradeNumber();
        $eloquent->trade_action = $confirmation->getTradeAction();
        $eloquent->position_effect = $confirmation->getPositionEffect();
        $eloquent->trade_quantity = $confirmation->getTradeQuantity();
        $eloquent->unit_type = $confirmation->getUnitType();
        $eloquent->unit_price = $confirmation->getUnitPrice();
        $eloquent->commission = $confirmation->getCommission();
        $eloquent->us_tax = $confirmation->getUsTax();
        $eloquent->expiration_date = $confirmation->getExpirationDate();
        
        return $eloquent;
    }
}
