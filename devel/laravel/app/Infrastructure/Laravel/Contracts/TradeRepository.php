<?php

namespace App\Infrastructure\Laravel\Contracts;

use App\Domain\Confirmation\{
    Model\Confirmation,
    ValueObjects\TradeNumber,
};

use App\Infrastructure\Laravel\Eloquent\Trade\{
    Dto\PersistedTradeDto,
};

interface TradeRepository
{
    public function findByTradeNumber(TradeNumber $tradeNumber): ?PersistedTradeDto;
    public function save(Confirmation $confirmation): void;
}
