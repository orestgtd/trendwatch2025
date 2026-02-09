<?php

namespace App\Domain\Security\ValueObjects;

use App\Domain\Kernel\{
    Identifiers\SecurityNumber,
    Identifiers\Symbol,
    Values\ExpirationDate,
    Values\UnitType,
};

use App\Domain\Security\{
    Expiration\ExpirationRule,
};

use App\Foundation\Date;

final class SecurityInfo
{
    private function __construct(
        public readonly SecurityNumber $securityNumber,
        public readonly Symbol $symbol,
        public readonly Description $canonicalDescription,
        public readonly UnitType $unitType,
        public readonly ExpirationRule $expirationRule,
    ) {}

    public static function from(
        SecurityNumber $securityNumber,
        Symbol $symbol,
        Description $canonicalDescription,
        UnitType $unitType,
        ExpirationRule $expirationRule,
    ): self
    {
        return new self(
            $securityNumber,
            $symbol,
            $canonicalDescription,
            $unitType,
            $expirationRule
        );
    }

    public function getExpirationDate(): ExpirationDate { return $this->expirationRule->getExpirationDate(); }
    public function isExpiredAsOf(Date $asOf): bool { return $this->expirationRule->isExpiredAsOf($asOf); }
}
