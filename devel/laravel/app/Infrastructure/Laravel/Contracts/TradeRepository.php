<?php

namespace App\Infrastructure\Laravel\Contracts;

use App\Domain\{
    Confirmation\Model\Confirmation,
    Confirmation\Record\ConfirmationRecord,
    Kernel\Identifiers\TradeNumber,
};

interface TradeRepository
{
    public function findByTradeNumber(TradeNumber $tradeNumber): ?ConfirmationRecord;
    public function save(Confirmation $confirmation): void;
}
