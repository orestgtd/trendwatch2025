<?php

namespace App\Infrastructure\Laravel\Eloquent\Trade\Casts;

use App\Domain\Confirmation\ValueObjects\UnitPrice;
use App\Infrastructure\Laravel\Eloquent\Casts\AbstractMoneyCast;

final class UnitPriceCast extends AbstractMoneyCast
{
    protected const VALUE_OBJECT_CLASS = UnitPrice::class;
}
