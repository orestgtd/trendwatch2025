<?php

namespace App\Infrastructure\Laravel\Eloquent\Trade\Casts;

use App\Domain\Confirmation\ValueObjects\PositionEffect;
use App\Infrastructure\Laravel\Eloquent\Casts\AbstractValueObjectCast;

final class PositionEffectCast extends AbstractValueObjectCast
{
    protected const VALUE_OBJECT_CLASS = PositionEffect::class;
}
