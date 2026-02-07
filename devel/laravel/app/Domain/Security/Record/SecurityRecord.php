<?php

namespace App\Domain\Security\Record;

use App\Domain\Kernel\{
    Identifiers\SecurityNumber,
    Identifiers\Symbol,
    Values\ExpirationDate,
    Values\UnitType,
};

use App\Domain\Security\{
    ValueObjects\Description,
    ValueObjects\Variations\VariationsInterface,
};

final class SecurityRecord
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
