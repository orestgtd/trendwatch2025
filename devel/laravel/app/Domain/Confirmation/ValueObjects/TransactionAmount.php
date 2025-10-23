<?php

namespace App\Domain\Confirmation\ValueObjects;

use App\Domain\Common\Money\{
    AbstractMoney,
};

use App\Domain\Confirmation\ValueObjects\{
    CostAmount,
    ProceedsAmount,
};

final class TransactionAmount extends AbstractMoney
{
    public function toCost(): CostAmount
    {
        return CostAmount::create(
            $this->amount,
            $this->currency
        );
    }

    public function toProceeds(): ProceedsAmount
    {
        return ProceedsAmount::create(
            $this->amount,
            $this->currency
        );
    }
}
