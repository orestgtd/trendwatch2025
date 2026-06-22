<?php

namespace App\Application\Expiration;

final class ExpirePositionsResult
{
    public function __construct(
        private readonly int $expiredCount
    ) {}

    public function expiredCount(): int
    {
        return $this->expiredCount;
    }
}

