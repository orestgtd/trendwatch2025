<?php

namespace App\Domain\Security\Model;

use App\Domain\Kernel\{
    Identifiers\SecurityNumber,
    Identifiers\Symbol,
    Values\ExpirationDate,
    Values\UnitType,
};

use App\Domain\Security\{
    Expiration\ExpirationRule,
    Model\Security,
    ValueObjects\Description,
    ValueObjects\ExpirationDate\ExpiresOn,
    ValueObjects\SecurityInfo,
    ValueObjects\Variations\VariationsInterface,
};

final class OptionSecurity extends Security
{
    public static function create(
        SecurityInfo $securityInfo,
        VariationsInterface $variations,
    ): self {

        return new self(
            $securityInfo,
            $variations,
        );
    }
}
