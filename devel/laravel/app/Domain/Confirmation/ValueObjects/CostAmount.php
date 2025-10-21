<?php

namespace App\Domain\Confirmation\ValueObjects;

use App\Domain\Common\Money\{
    AbstractMoney,
    Currency,
    MoneyAmount,
};

final class CostAmount extends AbstractMoney {}
