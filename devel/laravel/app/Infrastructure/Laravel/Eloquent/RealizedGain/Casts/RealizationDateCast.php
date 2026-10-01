<?php

namespace App\Infrastructure\Laravel\Eloquent\RealizedGain\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

use App\Domain\{
    Kernel\Values\RealizationDate,
};

final class RealizationDateCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): RealizationDate
    {
        return RealizationDate::tryFrom($value)
            ->match(
                fn (RealizationDate $realizationDate) => $realizationDate,
                fn (string $error) => throw new \LogicException($error)
            );
    }

    public function set($model, string $key, $value, array $attributes): ?string
    {
        return match (true) {
            is_null($value) => null,
            ($value instanceof RealizationDate) => (string) $value,
            default => $value,
        };
    }
}
