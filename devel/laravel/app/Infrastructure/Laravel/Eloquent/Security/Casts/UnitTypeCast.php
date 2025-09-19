<?php

namespace App\Infrastructure\Laravel\Eloquent\Security\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

use App\Domain\Security\ValueObjects\UnitType;

final class UnitTypeCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): UnitType
    {
        return UnitType::fromString($value);
    }

    public function set($model, string $key, $value, array $attributes): string
    {
        return match (true) {
            ($value instanceof UnitType) => (string) $value,
            default => $value,
        };
    }
}
