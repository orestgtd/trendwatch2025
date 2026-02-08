<?php

namespace App\Domain\Security\Builders;

use App\Domain\Security\{
    Expiration\ExpirationRule,
    Model\EquitySecurity,
    Model\OptionSecurity,
    Model\Security,
    Record\SecurityRecord,
    ValueObjects\SecurityInfo,
};

final class BuildSecurityFromRecord
{
    public static function from(SecurityRecord $record): Security
    {
        return $record->unitType->delegate(
            onContracts: fn () => self::buildOptionSecurityFromRecord($record),
            onShares: fn () => self::buildEquitySecurityFromRecord($record),
        );
    }

    private static function buildEquitySecurityFromRecord(SecurityRecord $record): EquitySecurity
    {
        return EquitySecurity::create(
            SecurityInfo::from(
                $record->securityNumber,
                $record->symbol,
                $record->canonicalDescription,
                $record->unitType,
                ExpirationRule::fromNullableDate($record->expirationDate),
            ),
            $record->variations
        );
    }

    private static function buildOptionSecurityFromRecord(SecurityRecord $record): OptionSecurity
    {
        return OptionSecurity::create(
            SecurityInfo::from(
                $record->securityNumber,
                $record->symbol,
                $record->canonicalDescription,
                $record->unitType,
                ExpirationRule::fromNullableDate($record->expirationDate),
            ),
            $record->variations
        );
    }
}
