<?php

namespace App\Infrastructure\Laravel\Eloquent\Trade\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

use App\Domain\Confirmation\ValueObjects\TradeQuantity;

final class TradeQuantityCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): TradeQuantity
    {
        return TradeQuantity::fromInt($value);
    }

    public function set($model, string $key, $value, array $attributes): string
    {
        return match (true) {
            ($value instanceof TradeQuantity) => (string) $value,
            default => $value,
        };
    }
}
