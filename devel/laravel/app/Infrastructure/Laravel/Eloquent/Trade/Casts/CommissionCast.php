<?php

namespace App\Infrastructure\Laravel\Eloquent\Trade\Casts;

use App\Domain\Confirmation\ValueObjects\Commission;
use App\Infrastructure\Laravel\Eloquent\Casts\AbstractMoneyCast;

final class CommissionCast extends AbstractMoneyCast
{
    protected const VALUE_OBJECT_CLASS = Commission::class;
}
