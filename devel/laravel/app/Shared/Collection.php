<?php

namespace App\Shared;

use \Illuminate\Support\Collection as LaravelCollection;

class Collection
{
    private function __construct(
        private LaravelCollection $collection
    ) {}

    public function __call($method, $arguments) {
        return $this->collection->$method($arguments);
    }

    public static function from(array $items): self
    {
        return new self(collect($items));
    }

    public function mapWithKeys(callable $callback): self
    {
        return new self($this->collection->mapWithKeys($callback));
    }

    public function reduce(callable $callback, mixed $initial = null): mixed
    {
        return $this->collection->reduce($callback, $initial);
    }
}