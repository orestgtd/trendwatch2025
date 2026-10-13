<?php

namespace App\Infrastructure\Laravel\Eloquent\Position\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

use App\Domain\Position\ValueObjects\PositionQuantity;

/** @implements CastsAttributes<PositionQuantity, string> */
final class PositionQuantityCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): PositionQuantity
    {
        return PositionQuantity::fromInt($value);
    }

    public function set($model, string $key, $value, array $attributes): string
    {
        return (string) $value;
    }
}
