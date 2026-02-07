<?php

namespace App\Infrastructure\Laravel\Contracts;

use App\Domain\{
    Confirmation\Model\Confirmation,
    Confirmation\Record\TradeRecord,
    Kernel\Identifiers\TradeNumber,
};

interface TradeRepository
{
    public function findByTradeNumber(TradeNumber $tradeNumber): ?TradeRecord;
    public function save(Confirmation $confirmation): void;
}
