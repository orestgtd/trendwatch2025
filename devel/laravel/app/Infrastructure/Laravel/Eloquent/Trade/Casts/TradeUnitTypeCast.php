<?php

namespace App\Infrastructure\Laravel\Eloquent\Trade\Casts;

use App\Domain\Confirmation\ValueObjects\TradeUnitType;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

final class TradeUnitTypeCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): TradeUnitType
    {
        return TradeUnitType::fromString($value);
    }

    public function set($model, string $key, $value, array $attributes): string
    {
        return match (true) {
            ($value instanceof TradeUnitType) => (string) $value,
            default => $value,
        };
    }
}
