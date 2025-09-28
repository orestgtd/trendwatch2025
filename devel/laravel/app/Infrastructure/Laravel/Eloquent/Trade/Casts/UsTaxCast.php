<?php

namespace App\Infrastructure\Laravel\Eloquent\Trade\Casts;

use App\Domain\Confirmation\ValueObjects\UsTax;
use App\Infrastructure\Laravel\Eloquent\Casts\AbstractMoneyCast;

final class UsTaxCast extends AbstractMoneyCast
{
    protected const VALUE_OBJECT_CLASS = UsTax::class;
}
