<?php

namespace App\Infrastructure\Laravel\Eloquent\Position\Casts;

use App\Domain\Position\ValueObjects\TotalCost;
use App\Infrastructure\Laravel\Eloquent\Casts\AbstractMoneyCast;

final class TotalCostCast extends AbstractMoneyCast
{
    protected const VALUE_OBJECT_CLASS = TotalCost::class;
}
