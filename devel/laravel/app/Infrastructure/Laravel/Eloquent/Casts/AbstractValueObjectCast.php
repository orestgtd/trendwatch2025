<?php

namespace App\Infrastructure\Laravel\Eloquent\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

abstract class AbstractValueObjectCast implements CastsAttributes
{
    /**
     * Fully-qualified class name of the Value Object.
     * Each concrete cast must define this constant.
     * e.g.     protected const VALUE_OBJECT_CLASS = TradeAction::class;
     */
    protected const VALUE_OBJECT_CLASS = '';

    public function get($model, string $key, $value, array $attributes): object
    {
        $class = static::VALUE_OBJECT_CLASS;

        if (!method_exists($class, 'fromString')) {
            throw new \LogicException("Class $class must have a fromString method.");
        }

        return $class::fromString($value);
    }

    public function set($model, string $key, $value, array $attributes): mixed
    {
        $class = static::VALUE_OBJECT_CLASS;

        return match (true) {
            $value instanceof $class => (string) $value,
            default => $value,
        };
    }
}
