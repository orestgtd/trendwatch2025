<?php

namespace App\Domain\Security\Model;

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

final class EquitySecurity extends AbstractSecurity
{
    public static function create(
        SecurityNumber $securityNumber,
        Symbol $symbol,
        Description $canonicalDescription,
        VariationsInterface $variations,
    ): self {

        return new self(
            $securityNumber,
            $symbol,
            UnitType::shares(),
            $canonicalDescription,
            $variations,
            ExpirationDate::never()
        );
    }
}
