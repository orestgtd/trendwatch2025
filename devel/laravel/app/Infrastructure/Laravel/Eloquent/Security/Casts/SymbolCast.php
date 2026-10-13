<?php

namespace App\Infrastructure\Laravel\Eloquent\Security\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

use App\Domain\{
    Kernel\Identifiers\Symbol,
};

/** @implements CastsAttributes<Symbol, string> */
final class SymbolCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): Symbol
    {
        return Symbol::fromString($value);
    }

    public function set($model, string $key, $value, array $attributes): string
    {
        return (string) $value;
    }
}
