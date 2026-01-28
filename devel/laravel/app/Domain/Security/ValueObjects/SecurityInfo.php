<?php

namespace App\Domain\Security\ValueObjects;

use App\Domain\Kernel\Values\ExpirationDate;

use App\Domain\Kernel\{
    Identifiers\SecurityNumber,
    Identifiers\Symbol,
    Values\UnitType,
};

use App\Shared\Date;

final class SecurityInfo
{
    private function __construct(
        public readonly SecurityNumber $securityNumber,
        public readonly Symbol $symbol,
        public readonly Description $canonicalDescription,
        public readonly UnitType $unitType,
        public readonly ExpirationDate $expirationDate,
    ) {}

    public static function from(
        SecurityNumber $securityNumber,
        Symbol $symbol,
        Description $canonicalDescription,
        UnitType $unitType,
        ExpirationDate $expirationDate,
    ): self
    {
        return new self(
            $securityNumber,
            $symbol,
            $canonicalDescription,
            $unitType,
            $expirationDate
        );
    }

    public function getExpirationDate(): ExpirationDate { return $this->expirationDate; }
    public function isExpiredAsOf(Date $asOf): bool { return $this->expirationDate->isExpiredAsOf($asOf); }

}
