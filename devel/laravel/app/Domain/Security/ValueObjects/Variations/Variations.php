<?php

namespace App\Domain\Security\ValueObjects\Variations;

use App\Domain\Security\ValueObjects\Description;

final class Variations implements VariationsInterface
{
    private array $variations;

    private function __construct(array $variations)
    {
        $this->variations = $variations;
    }

    /**
     * Static helper to create a Variations object with a single Description.
     * Used primarily by NoVariations when appending the first Description.
     */
    public static function create(Description $variation): self
    {
        return new self([$variation]);
    }

    /**
     * Build a Variations object from an array of description strings.
     * Returns NoVariations if the array is empty.
     */
    public static function fromStrings(array $strings): VariationsInterface
    {
        return empty($strings)
            ? NoVariations::create()
            : new self(array_map(fn(string $s) => Description::fromString($s), $strings));
    }

    public function add(Description $variation): VariationsInterface
    {
        $newVariations = $this->variations;
        $newVariations[] = $variation;

        return new self($newVariations);
    }

    public function contains(Description $description): bool
    {
        return (bool) array_filter(
            $this->variations,
            fn (Description $v) => $v->equals($description)
        );
    }

    public function all(): array
    {
        return $this->variations;
    }
}
