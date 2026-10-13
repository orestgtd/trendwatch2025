<?php

namespace App\Infrastructure\Laravel\Eloquent\Security\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

use App\Domain\Security\ValueObjects\Description;

/** @implements CastsAttributes<Description, string> */
final class DescriptionCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): Description
    {
        return Description::fromString($value);
    }

    public function set($model, string $key, $value, array $attributes): string
    {
        return (string) $value;
    }
}
