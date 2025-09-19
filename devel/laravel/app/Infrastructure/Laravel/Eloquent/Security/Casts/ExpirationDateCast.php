<?php

namespace App\Infrastructure\Laravel\Eloquent\Security\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

use App\Domain\Security\ValueObjects\ExpirationDate\{
    ExpirationDate,
    ExpirationDateInterface,
    NoExpiration,
};

use App\Shared\Date;

final class ExpirationDateCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): ExpirationDateInterface
    {
        return is_null($value)
            ? NoExpiration::create()
            : ExpirationDate::create(Date::fromString($value));
    }

    public function set($model, string $key, $value, array $attributes): string
    {
        return match (true) {
            ($value instanceof ExpirationDateInterface) => (string) $value,
            default => $value,
        };
    }
}
