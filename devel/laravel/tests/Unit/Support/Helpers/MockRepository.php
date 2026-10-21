<?php

namespace Tests\Unit\Support\Helpers;

use App\Application\{
    Contracts\PositionRepositoryContract,
    Contracts\RealizedGainRepositoryContract,
};

class MockRepository
{
    use MockTrait;

    public static function findPositionBySecurityNumber(mixed $returnValue): void
    {
        static::mock(
            PositionRepositoryContract::class,
            'findBySecurityNumber',
            $returnValue
        );
    }

    public static function findAllRealizedGains(mixed $returnValue): void
    {
        static::mock(
            RealizedGainRepositoryContract::class,
            'all',
            $returnValue
        );
    }
}
