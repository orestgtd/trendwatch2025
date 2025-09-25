<?php

namespace App\Infrastructure\Laravel\Eloquent\Trade\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

use App\Domain\Confirmation\ValueObjects\PositionEffect;

final class PositionEffectCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): PositionEffect
    {
        return PositionEffect::fromString($value);
    }

    public function set($model, string $key, $value, array $attributes): string
    {
        return match (true) {
            ($value instanceof PositionEffect) => (string) $value,
            default => $value,
        };
    }
}
