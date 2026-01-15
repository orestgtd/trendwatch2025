<?php

namespace App\Infrastructure\Laravel\Eloquent\Security\Dto;

use App\Domain\Kernel\{
    Identifiers\SecurityNumber,
    Identifiers\Symbol,
    Values\ExpirationDate,
    Values\UnitType,
};

use App\Domain\Security\ValueObjects\{
    Description,
    Variations\VariationsInterface,
};

final class PersistedSecurityDto
{
    public function __construct(
        public readonly SecurityNumber $securityNumber,
        public readonly Symbol $symbol,
        public readonly Description $canonicalDescription,
        public readonly VariationsInterface $variations,
        public readonly UnitType $unitType,
        public readonly ?ExpirationDate $expirationDate,
    ) {}
}
