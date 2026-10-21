<?php

namespace Tests\Unit\Support\Helpers;

use Mockery;

trait MockTrait
{
    public static function mock(string $objectName, string $methodName, mixed $returnValue): void
    {
        $mockRepository = Mockery::mock($objectName);
        app()->instance($objectName, $mockRepository);
        $mockRepository->shouldReceive($methodName)->andReturn($returnValue);
    }
}
