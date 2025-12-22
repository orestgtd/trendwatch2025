<?php

namespace App\Infrastructure\Laravel\Eloquent\Trade\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

use App\Domain\Kernel\Identifiers\{
    TradeNumber,
};

final class TradeNumberCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): TradeNumber
    {
        return TradeNumber::fromString($value);
    }

    public function set($model, string $key, $value, array $attributes): string
    {
        return match (true) {
            ($value instanceof TradeNumber) => (string) $value,
            default => $value,
        };
    }
}
