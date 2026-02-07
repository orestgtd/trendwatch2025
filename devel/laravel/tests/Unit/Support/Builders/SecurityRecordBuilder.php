<?php

namespace Tests\Unit\Support\Builders;

use App\Domain\Kernel\{
    Identifiers\SecurityNumber,
    Identifiers\Symbol,
    Values\UnitType,
};

use App\Domain\Security\{
    ValueObjects\Description,
    ValueObjects\Variations\NoVariations,
    Record\SecurityRecord,
};

final class SecurityRecordBuilder
{
    public static function YYZ(): SecurityRecord
    {
        return new SecurityRecord(
            SecurityNumber::fromString('2112'),
            Symbol::fromString('YYZ'),
            Description::fromString('SECURITY UNDER PRESSURE'),
            NoVariations::create(),
            UnitType::shares(),
            null
        );
    }
}
