<?php

namespace App\Domain\Security\ValueObjects\Variations;

use App\Domain\Security\ValueObjects\Description;

interface VariationsInterface
{
    /**
     * Return a new VariationsInterface with the new description appended.
     */
    public function add(Description $description): self;

    public function contains(Description $description): bool;

    /** @return Description[] */
    public function all(): array;

}
