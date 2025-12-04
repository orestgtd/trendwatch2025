<?php

namespace Tests\Unit\Support\Helpers;

use Mockery;

class MockObject
{
    public static function mock(string $objectName, string $methodName, mixed $returnValue): void
    {
        $mockRepository = Mockery::mock($objectName);
        app()->instance($objectName, $mockRepository);
        $mockRepository->shouldReceive($methodName)->andReturn($returnValue);
    }
}
