<?php

namespace App\Infrastructure\Laravel\Eloquent\Position\Casts;

use App\Domain\Position\ValueObjects\TotalProceeds;
use App\Infrastructure\Laravel\Eloquent\Casts\AbstractMoneyCast;

final class TotalProceedsCast extends AbstractMoneyCast
{
    protected const VALUE_OBJECT_CLASS = TotalProceeds::class;
}
