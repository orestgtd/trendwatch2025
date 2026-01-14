<?php

namespace App\Domain\Security\Builders;

use App\Domain\Kernel\{
    Identifiers\SecurityNumber,
    Identifiers\Symbol,
    Values\UnitType,
};

use App\Domain\Security\Model\{
    EquitySecurity,
    OptionSecurity,
    Security,
};

use App\Domain\Security\ValueObjects\{
    Description,
    ExpirationDate\ExpirationDateInterface,
    Variations\VariationsInterface,
};

final class BuildSecurityFrom
{
    public static function from(
        SecurityNumber $securityNumber,
        Symbol $symbol,
        Description $description,
        VariationsInterface $variations,
        UnitType $unitType,
        ExpirationDateInterface $expirationDate
    ): Security {
        return $unitType->delegate(
            onContracts: fn () => OptionSecurity::create($securityNumber, $symbol, $description, $variations, $expirationDate),
            onShares: fn () => EquitySecurity::create($securityNumber, $symbol, $description, $variations),
        );
    }
}
