<?php

namespace App\Infrastructure\Laravel\Eloquent\RealizedGain\Casts;

use App\Domain\Kernel\{
    Identifiers\TradeNumber,
};

use App\Domain\RealizedGain\{
    ValueObjects\RealizationSource,
    ValueObjects\RealizationSourceType,
};

use App\Shared\Date;

use Illuminate\{
    Contracts\Database\Eloquent\CastsAttributes,
    Database\Eloquent\Model,
};

final class RealizationSourceCast implements CastsAttributes
{
    /**
     * Fully-qualified class name of the Money-based Value Object.
     * Each concrete cast must define this constant.
     * e.g.     protected const VALUE_OBJECT_CLASS = UnitPrice::class;
     */
    protected const VALUE_OBJECT_CLASS = RealizationSource::class;

    /**
     * @param  Model  $model
     * @param  string $key
     * @param  mixed  $value
     * @param  array<string, mixed> $attributes
     */
    public function get($model, string $key, $value, array $attributes): RealizationSource
    {
        // $class = static::VALUE_OBJECT_CLASS;

        $typeKey   = $key . '_type';
        $referenceKey = $key . '_reference';

        $type  = $attributes[$typeKey]  ?? null;
        $reference = $attributes[$referenceKey] ?? null;


        if (! isset($attributes[$typeKey], $attributes[$referenceKey])) {
            throw new \LogicException('Realization source columns are missing or null.');
        }

        return match ($type) {
            RealizationSourceType::TRADE =>
                RealizationSource::trade(
                    TradeNumber::fromString($reference)
                ),

            RealizationSourceType::EXPIRATION =>
                RealizationSource::expiration(
                    Date::fromString($reference)
                ),

            default =>
                throw new \LogicException("Unknown realization source type: {$type}"),
        };
    }

    /**
     * @param  Model  $model
     * @param  string $key
     * @param  RealizationSource $value
     * @param  array<string, mixed> $attributes
     * @return array<string, mixed>
     */
    public function set($model, string $key, $value, array $attributes): array
    {
        $class = static::VALUE_OBJECT_CLASS;

        $typeKey   = $key . '_type';
        $referenceKey = $key . '_reference';

        if (! $value instanceof $class) {
            return [
                $typeKey   => null,
                $referenceKey => null,
            ];
        }

        return [
            $typeKey   => $value->getType(),
            $referenceKey => $value->getReference(),
        ];
    }
}
