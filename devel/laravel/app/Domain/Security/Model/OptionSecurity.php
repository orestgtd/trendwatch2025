<?php

namespace App\Domain\Security\Model;

use App\Domain\Kernel\{
    Identifiers\SecurityNumber,
    Identifiers\Symbol,
    Values\UnitType,
};

use App\Domain\Security\{
    Model\Security,
    ValueObjects\Description,
    ValueObjects\ExpirationDate\ExpiresOn,
    ValueObjects\Variations\VariationsInterface,
};

final class OptionSecurity extends Security
{
    public static function create(
        SecurityNumber $securityNumber,
        Symbol $symbol,
        Description $canonicalDescription,
        VariationsInterface $variations,
        ExpiresOn $expirationDate
    ): self {

        return new self(
            $securityNumber,
            $symbol,
            UnitType::contracts(),
            $canonicalDescription,
            $variations,
            $expirationDate
        );
    }
}
