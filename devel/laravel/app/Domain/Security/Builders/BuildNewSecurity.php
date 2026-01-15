<?php

namespace App\Domain\Security\Builders;

use App\Domain\Kernel\{
    Identifiers\SecurityNumber,
    Identifiers\Symbol,
    Values\ExpirationDate,
    Values\UnitType,
};

use App\Domain\Security\{
    Model\EquitySecurity,
    Model\OptionSecurity,
    Model\Security,
    ValueObjects\Description,
    ValueObjects\Variations\VariationsInterface,
};
use App\Shared\Result;

final class BuildNewSecurity
{
    /** @return Result<Security> */
    public static function tryFrom(
        SecurityNumber $securityNumber,
        Symbol $symbol,
        Description $description,
        VariationsInterface $variations,
        UnitType $unitType,
        ExpirationDate $expirationDate
    ): Result {

        return match (true)
        {
            $unitType->equals(UnitType::contracts()) => self::buildOption($securityNumber, $symbol, $description, $variations, $expirationDate),
            $unitType->equals(UnitType::shares()) => self::buildEquity($securityNumber, $symbol, $description, $variations),
            default => Result::failure('Unsupported UnitType: ' . $unitType)
        };

    }

    /** @return Result<Security> */
    private static function buildEquity(
        SecurityNumber $securityNumber,
        Symbol $symbol,
        Description $description,
        VariationsInterface $variations,
    ): Result
    {
        return Result::success(
            EquitySecurity::create($securityNumber, $symbol, $description, $variations)
        );
    }

    /** @return Result<Security> */
    private static function buildOption(
        SecurityNumber $securityNumber,
        Symbol $symbol,
        Description $description,
        VariationsInterface $variations,
        ExpirationDate $expirationDate
    ): Result
    {
        if (!$expirationDate->hasDate()) {
            return Result::failure('OptionSecurity requires a valid ExpirationDate');
        }
        return Result::success(
            OptionSecurity::create($securityNumber, $symbol, $description, $variations, $expirationDate)
        );
    }
}
