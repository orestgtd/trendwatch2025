<?php

namespace Tests\Unit\Support\Helpers;

use App\Application\Contracts\PositionRepositoryContract;
use Mockery;

class MockObject
{
    public static function mock(string $objectName, string $methodName, mixed $returnValue): void
    {
        $mockRepository = Mockery::mock($objectName);
        app()->instance($objectName, $mockRepository);
        $mockRepository->shouldReceive($methodName)->andReturn($returnValue);
    }

    public static function findPositionBySecurityNumber(mixed $returnValue): void
    {
        static::mock(
            PositionRepositoryContract::class,
            'findBySecurityNumber',
            $returnValue
        );
    }
}
