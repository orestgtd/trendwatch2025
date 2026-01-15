<?php

namespace App\Domain\Security\Model;

use App\Domain\Kernel\{
    Identifiers\SecurityNumber,
    Identifiers\Symbol,
    Values\UnitType,
};

use App\Domain\Security\ValueObjects\{
    Description,
    // ExpirationDate\ExpirationDate,
    ExpirationDate\NeverExpires,
    Variations\VariationsInterface,
};

final class EquitySecurity extends AbstractSecurity
{
    private function __construct(
        SecurityNumber $securityNumber,
        Symbol $symbol,
        Description $canonicalDescription,
        VariationsInterface $variations
    ) {
        parent::__construct(
            $securityNumber,
            $symbol,
            UnitType::shares(),
            $canonicalDescription,
            $variations,
        );
    }

    public static function create(
        SecurityNumber $securityNumber,
        Symbol $symbol,
        Description $canonicalDescription,
        VariationsInterface $variations,
    ): self {

        return new self(
            $securityNumber,
            $symbol,
            $canonicalDescription,
            $variations,
        );
    }

    // public function securityNumber(): SecurityNumber { return $this->securityNumber; }
    // public function symbol(): Symbol { return $this->symbol; }
    // public function canonicalDescription(): Description { return $this->canonicalDescription; }
    // public function variations(): VariationsInterface { return $this->variations; }

    public function expirationDate(): NeverExpires
    {
        // Equities never expire
        return NeverExpires::create();
    }
}
