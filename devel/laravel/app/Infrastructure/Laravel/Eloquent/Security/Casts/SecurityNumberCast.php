<?php

namespace App\Infrastructure\Laravel\Eloquent\Security\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

use App\Domain\Security\ValueObjects\SecurityNumber;

final class SecurityNumberCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): SecurityNumber
    {
        return SecurityNumber::fromString($value);
    }

    public function set($model, string $key, $value, array $attributes): string
    {
        return match (true) {
            ($value instanceof SecurityNumber) => (string) $value,
            default => $value,
        };
    }
}
