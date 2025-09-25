<?php

namespace App\Infrastructure\Laravel\Eloquent\Trade\Casts;

use App\Domain\Confirmation\ValueObjects\TradeAction;
use App\Infrastructure\Laravel\Eloquent\Casts\AbstractValueObjectCast;

final class TradeActionCast extends AbstractValueObjectCast
{
    protected const VALUE_OBJECT_CLASS = TradeAction::class;
}
