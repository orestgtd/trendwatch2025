<?php

namespace App\Infrastructure\Laravel\Eloquent\Trade\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

use App\Domain\Confirmation\ValueObjects\TradeQuantity;

/** @implements CastsAttributes<TradeQuantity, string> */
final class TradeQuantityCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): TradeQuantity
    {
        return TradeQuantity::fromInt($value);
    }

    public function set($model, string $key, $value, array $attributes): string
    {
        return (string) $value;
    }
}
