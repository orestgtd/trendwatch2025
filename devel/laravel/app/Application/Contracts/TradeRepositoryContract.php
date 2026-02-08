<?php

namespace App\Application\Contracts;

use App\Domain\{
    Confirmation\Model\Confirmation,
    Confirmation\Record\ConfirmationRecord,
    Kernel\Identifiers\TradeNumber,
};

interface TradeRepositoryContract
{
    // Commands
    public function insert(Confirmation $confirmation): void;

    // Queries
    public function findByTradeNumber(TradeNumber $tradeNumber): ?ConfirmationRecord;
}
