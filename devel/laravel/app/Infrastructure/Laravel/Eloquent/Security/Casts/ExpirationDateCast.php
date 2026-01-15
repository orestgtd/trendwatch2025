<?php

namespace App\Infrastructure\Laravel\Eloquent\Security\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

use App\Domain\Security\ValueObjects\ExpirationDate\{
    ExpirationDate,
    ExpiresOn,
    NeverExpires,
};

use App\Shared\Date;

final class ExpirationDateCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): ExpirationDate
    {
        return is_null($value)
            ? NeverExpires::create()
            : ExpiresOn::create(Date::fromString($value));
    }

    public function set($model, string $key, $value, array $attributes): string
    {
        return match (true) {
            ($value instanceof ExpiresOn) => (string) $value,
            ($value instanceof NeverExpires) => '',
            default => $value,
        };
    }
}
