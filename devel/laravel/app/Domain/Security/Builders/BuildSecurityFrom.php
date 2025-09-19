<?php

namespace App\Domain\Security\Builders;

use App\Domain\Security\Model\{
    EquitySecurity,
    OptionSecurity,
    Security,
};

use App\Domain\Security\ValueObjects\{
    Description,
    ExpirationDate\ExpirationDateInterface,
    SecurityNumber,
    Symbol,
    UnitType,
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
        return
            match ((string) $unitType) {
                UnitType::CONTRACTS => OptionSecurity::create($securityNumber, $symbol, $description, $variations, $expirationDate),
                UnitType::SHARES => EquitySecurity::create($securityNumber, $symbol, $description, $variations),
            };
    }
}
