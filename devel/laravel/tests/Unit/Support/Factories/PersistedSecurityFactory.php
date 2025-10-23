<?php

namespace Tests\Unit\Support\Factories;

use App\Domain\Security\ValueObjects\{
    Description,
    ExpirationDate\NoExpiration,
    SecurityNumber,
    Symbol,
    UnitType,
    Variations\NoVariations,
};

use App\Infrastructure\Laravel\Eloquent\Security\{
    Dto\PersistedSecurityDto,
};

final class PersistedSecurityFactory
{
    public static function YYZ(): PersistedSecurityDto
    {
        return new PersistedSecurityDto(
            SecurityNumber::fromString('2112'),
            Symbol::fromString('YYZ'),
            Description::fromString('SECURITY UNDER PRESSURE'),
            NoVariations::create(),
            UnitType::shares(),
            NoExpiration::create(),
        );
    }
}
