<?php

namespace App\Domain\Security\Builders;

use App\Domain\Security\{
    Model\EquitySecurity,
    Model\OptionSecurity,
    Model\Security,
    ValueObjects\SecurityInfo,
    ValueObjects\Variations\VariationsInterface,
};

final class BuildSecurityFrom
{
    public static function from(
        SecurityInfo $securityInfo,
        VariationsInterface $variations,
    ): Security {
        return $securityInfo->unitType->delegate(
            onContracts: fn () => OptionSecurity::create($securityInfo, $variations),
            onShares: fn () => EquitySecurity::create($securityInfo, $variations),
        );
    }
}
