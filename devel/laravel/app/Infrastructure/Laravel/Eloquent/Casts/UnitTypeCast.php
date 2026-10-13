<?php

namespace App\Infrastructure\Laravel\Eloquent\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

use App\Domain\Kernel\{
    Values\UnitType,
};

/** @implements CastsAttributes<UnitType, string> */
final class UnitTypeCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): UnitType
    {
        return UnitType::fromString($value);
    }

    public function set($model, string $key, $value, array $attributes): string
    {
        return (string) $value;
    }
}
