<?php

namespace App\Infrastructure\Laravel\Eloquent\Position\Casts;

use App\Domain\Position\ValueObjects\PositionType;
use App\Infrastructure\Laravel\Eloquent\Casts\AbstractValueObjectCast;

final class PositionTypeCast extends AbstractValueObjectCast
{
    protected const VALUE_OBJECT_CLASS = PositionType::class;
}
