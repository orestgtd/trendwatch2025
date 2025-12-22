<?php

namespace App\Infrastructure\Laravel\Eloquent\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

use App\Domain\Kernel\{
    Values\UnitType,
};

final class UnitTypeCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): UnitType
    {
        if (is_null($value))
        {
            dd([
                'model' => $model,
                'key' => $key,
                'value' => $value,
            ]);
        }

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
