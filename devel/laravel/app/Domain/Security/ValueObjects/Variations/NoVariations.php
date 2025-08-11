<?php

namespace App\Domain\Security\ValueObjects\Variations;

use App\Domain\Security\ValueObjects\Description;

final class NoVariations implements VariationsInterface
{
    private function __construct() {}

    public static function create(): self
    {
        return new self();
    }

    public function add(Description $variation): VariationsInterface
    {
        // Moving from "no variations" to one variation
        return Variations::create($variation);
    }

    public function contains(Description $description): bool
    {
        return false;
    }

    public function all(): array
    {
        return [];
    }
}
