<?php

namespace App\Infrastructure\Laravel\Eloquent\Position\Casts;

use App\Domain\Confirmation\ValueObjects\{
    ProceedsAmount,
};

use App\Infrastructure\Laravel\Eloquent\Casts\AbstractMoneyCast;

final class ProceedsAmountCast extends AbstractMoneyCast
{
    protected const VALUE_OBJECT_CLASS = ProceedsAmount::class;
}
