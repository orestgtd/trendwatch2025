<?php

namespace App\Infrastructure\Laravel\Contracts;

use App\Domain\Confirmation\{
    Model\Confirmation,
    ValueObjects\TradeNumber,
};

use App\Infrastructure\Laravel\Eloquent\Trade\{
    DTO\PersistedTradeDTO,
};

interface TradeRepository
{
    public function findByTradeNumber(TradeNumber $tradeNumber): ?PersistedTradeDTO;
    public function save(Confirmation $confirmation): void;
}
