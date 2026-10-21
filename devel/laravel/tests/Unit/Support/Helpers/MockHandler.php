<?php

namespace Tests\Unit\Support\Helpers;

use App\Application\Reports\GetExpirablePositions;

class MockHandler
{
    use MockTrait;

    public static function handleExpirablePositions(mixed $result): void
    {
        static::mock(
            GetExpirablePositions::class,
            'handle',
            $result
        );
    }
}
