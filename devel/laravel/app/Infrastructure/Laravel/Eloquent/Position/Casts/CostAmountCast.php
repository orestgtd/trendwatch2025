<?php

namespace App\Infrastructure\Laravel\Eloquent\Position\Casts;

use App\Domain\Confirmation\ValueObjects\{
    CostAmount,
};

use App\Infrastructure\Laravel\Eloquent\Casts\AbstractMoneyCast;

final class CostAmountCast extends AbstractMoneyCast
{
    protected const VALUE_OBJECT_CLASS = CostAmount::class;
}
