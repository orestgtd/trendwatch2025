<?php

namespace App\Shared;

use \Illuminate\Support\Collection as LaravelCollection;

/**
 * @template TKey
 * @template TValue
 */
class Collection
{
    /**
     * @param LaravelCollection<TKey, TValue> $collection
     */
    private function __construct(
        private LaravelCollection $collection
    ) {}

    public function __call($method, $arguments) {
        return $this->collection->$method(...$arguments);
    }

   /**
     * @param array<TKey, TValue> $items
     * @return self<TKey, TValue>
     */
    public static function from(array $items): self
    {
        return new self(collect($items));
    }

   /**
     * @param callable(TValue, TKey): array<TKey2, TValue2> $callback
     * @return Collection<TKey2, TValue2>
     * @template TKey2
     * @template TValue2
     */
    public function mapWithKeys(callable $callback): self
    {
        return new self($this->collection->mapWithKeys($callback));
    }

   /**
     * @param callable(TValue, TKey, mixed $carry): mixed $callback
     * @param mixed $initial
     * @return mixed
     */
     public function reduce(callable $callback, mixed $initial = null): mixed
    {
        return $this->collection->reduce($callback, $initial);
    }
}