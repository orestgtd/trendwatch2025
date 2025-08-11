<?php

namespace App\Domain\Security;

use App\Domain\Security\Model\{
    EquitySecurity,
    OptionSecurity,
};

use App\Domain\Security\ValueObjects\{
    Description,
    ExpirationDate\ExpirationDateInterface,
    SecurityNumber,
    Symbol,
    UnitType,
    Variations\VariationsInterface,
};

use App\Shared\Result;

final class SecurityFactory
{
    /** @return Result<Security> */
    public static function tryFrom(
        SecurityNumber $securityNumber,
        Symbol $symbol,
        Description $description,
        VariationsInterface $variations,
        UnitType $unitType,
        ExpirationDateInterface $expirationDate
    ): Result {
        if ($unitType->equals(UnitType::contracts())) {
            if (!$expirationDate->hasDate()) {
                return Result::failure('OptionSecurity requires a valid ExpirationDate');
            }
            return Result::success(OptionSecurity::create($securityNumber, $symbol, $description, $variations, $expirationDate));
        }

        if ($unitType->equals(UnitType::shares())) {
            return Result::success(EquitySecurity::create($securityNumber, $symbol, $description, $variations));
        }

        return Result::failure('Unsupported UnitType: ' . $unitType);
    }
}
