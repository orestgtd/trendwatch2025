<?php

namespace App\Infrastructure\Laravel\Eloquent\Security\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

use App\Domain\{
    Kernel\Identifiers\Symbol,
};

final class SymbolCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): Symbol
    {
        return Symbol::fromString($value);
    }

    public function set($model, string $key, $value, array $attributes): string
    {
        return match (true) {
            ($value instanceof Symbol) => (string) $value,
            default => $value,
        };
    }
}
