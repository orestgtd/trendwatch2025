<?php

namespace App\Domain\Security\Dto;

final class SecurityData
{
    public function __construct(
        public readonly string $securityNumber,
        public readonly string $symbol,
        public readonly string $description,
        public readonly array $variations,
        public readonly string $unitType,
        public readonly string $expirationDate
    ) {}
}
