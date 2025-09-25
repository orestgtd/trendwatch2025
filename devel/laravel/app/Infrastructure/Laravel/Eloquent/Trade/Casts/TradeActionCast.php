<?php

namespace App\Infrastructure\Laravel\Eloquent\Trade\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

use App\Domain\Confirmation\ValueObjects\TradeAction;

final class TradeActionCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): TradeAction
    {
        return TradeAction::fromString($value);
    }

    public function set($model, string $key, $value, array $attributes): string
    {
        return match (true) {
            ($value instanceof TradeAction) => (string) $value,
            default => $value,
        };
    }
}
