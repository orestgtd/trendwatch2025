<?php

namespace App\Infrastructure\Laravel\Eloquent\RealizedGain\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

use App\Domain\Position\ValueObjects\BaseQuantity;

final class BaseQuantityCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): BaseQuantity
    {
        return BaseQuantity::fromInt($value);
    }

    public function set($model, string $key, $value, array $attributes): string
    {
        return match (true) {
            ($value instanceof BaseQuantity) => (string) $value,
            default => $value,
        };
    }
}
