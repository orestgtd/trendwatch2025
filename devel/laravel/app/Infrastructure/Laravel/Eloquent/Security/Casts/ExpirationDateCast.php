<?php

namespace App\Infrastructure\Laravel\Eloquent\Security\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

use App\Domain\{
    Kernel\Values\ExpirationDate,
    Security\Expiration\ExpirationRule,
};

final class ExpirationDateCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): ?ExpirationDate
    {
        if (is_null($value) || $value === '') {
            return null;
        }

        return ExpirationDate::tryFrom($value)
            ->match(
                fn (ExpirationDate $expirationDate) => $expirationDate,
                fn (string $error) => throw new \LogicException($error)
            );
    }

    public function set($model, string $key, $value, array $attributes): ?string
    {
        return match (true) {
            is_null($value) => null,
            ($value instanceof ExpirationDate) => (string) $value,
            ($value instanceof ExpirationRule) => (string) $value,
            default => $value,
        };
    }
}
