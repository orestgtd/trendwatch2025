<?php

namespace Tests\Unit\Presentation\Console\Commands;

use Tests\{
    Support\Builders\PositionBuilder,
    TestCase,
};

use App\Presentation\{
    Console\Commands\Queries\GetExpirablePositionsCommand,
};

use App\Foundation\{
    Date,
    Result,
};

pest()->extend(TestCase::class);

it('lists one expirable position', function () {

    // Arrange one position

    $position1 = PositionBuilder::LongCall(
        expirationDate: Date::fromString('2022-08-19')
    )
        ->withSecurityNumber('R40')
        ->build();

    givenExpirablePositionHandles(
        Result::success([$position1])
    );

    // Act & Assert
    $this->artisan(GetExpirablePositionsCommand::class)
        ->expectsOutput('1 positions listed.')
        ->assertExitCode(0);
});

it('lists two expirable positions', function () {

    // Arrange two positions

    $position1 = PositionBuilder::LongCall(Date::fromString('2022-08-19'))
        ->withSecurityNumber('R40')
        ->build();
    $position2 = PositionBuilder::LongCall(Date::fromString('2022-08-19'))
        ->withSecurityNumber('R50')
        ->build();

    givenExpirablePositionHandles(
        Result::success([
            $position1,
            $position2,
        ])
    );

    // Act & Assert
    $this->artisan(GetExpirablePositionsCommand::class)
        ->expectsOutput('2 positions listed.')
        ->assertExitCode(0);
});

it('reports when no expired positions are found', function () {

    // Arrange zero positions
    givenExpirablePositionHandles(
        Result::success([])
    );

    // Act & Assert
    $this->artisan(GetExpirablePositionsCommand::class)
        ->expectsOutput('No expired positions found.')
        ->assertExitCode(0);
});
