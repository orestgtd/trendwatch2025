<?php

namespace App\Infrastructure\Laravel\Eloquent\Security\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

use App\Domain\{
    Kernel\Values\ExpirationDate,
};

use App\Shared\Date;

final class ExpirationDateCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): ExpirationDate
    {
        return ExpirationDate::from($value);
    }

    public function set($model, string $key, $value, array $attributes): string
    {
        return match (true) {
            ($value instanceof ExpirationDate) => (string) $value,
            default => $value,
        };
    }
}
