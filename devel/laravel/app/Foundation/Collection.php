<?php

/**
 * App\Foundation
 *
 * Language-level primitives and control-flow abstractions.
 * These types are domain-agnostic and may be used in any layer.
 */

namespace App\Foundation;

use \Illuminate\Support\Collection as LaravelCollection;

/**
 * @template TKey of array-key
 * @template TValue
 *
 * @mixin \Illuminate\Support\Collection<TKey, TValue>
 *
 * @method Collection<TKey, mixed> map(callable(TValue, TKey): mixed $callback)
 * @method mixed reduce(callable(mixed, TValue, TKey): mixed $callback, mixed $initial)
 * @method int count()
 */
class Collection
{
    /**
     * @param LaravelCollection<TKey, TValue> $collection
     */
    private function __construct(
        private readonly LaravelCollection $collection
    ) {}

    /**
     * Named constructor — the only public entry point
     *
     * @param array<TKey, TValue> $items
     * @return self<TKey, TValue>
     */
    public static function from(array $items): self
    {
        return new self(new LaravelCollection($items));
    }

    public function __call(string $method, array $arguments)
    {
        $returnValue = $this->collection->$method(...$arguments);

        if ($returnValue instanceof LaravelCollection) {
            return new self($returnValue);
        }

        return $returnValue;
    }
}