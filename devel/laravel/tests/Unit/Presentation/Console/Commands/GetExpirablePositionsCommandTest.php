<?php

namespace Tests\Unit\Presentation\Console\Commands;

use Mockery;
use Tests\{
    TestCase,
    Unit\Support\Builders\PositionBuilder,
};

use App\Application\{
    GetExpirablePositions\GetExpirablePositions,
};

use App\Presentation\{
    Console\Commands\GetExpirablePositionsCommand,
};

use App\Shared\{
    Date,
    Result,
};

class GetExpirablePositionsCommandTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_one_item(): void
    {
        // Arrange

        $position1 = PositionBuilder::LongCall(Date::fromString('2022-08-19'))
            ->withSecurityNumber('R40')
            ->build();

        $mockUseCase = Mockery::mock(GetExpirablePositions::class);
        $mockUseCase->shouldReceive('handle')
            ->andReturn(Result::success([
                $position1,
            ]));
        
        $this->app->instance(GetExpirablePositions::class, $mockUseCase);
        
        // Act & Assert
        $this->artisan(GetExpirablePositionsCommand::class)
            ->expectsOutput('1 positions listed.')
            ->assertExitCode(0);
    }

    public function test_two_items(): void
    {
        // Arrange

        $position1 = PositionBuilder::LongCall(Date::fromString('2022-08-19'))
            ->withSecurityNumber('R40')
            ->build();
        $position2 = PositionBuilder::LongCall(Date::fromString('2022-08-19'))
            ->withSecurityNumber('R50')
            ->build();

        $mockUseCase = Mockery::mock(GetExpirablePositions::class);
        $mockUseCase->shouldReceive('handle')
            ->andReturn(Result::success([
                $position1,
                $position2,
            ]));
        
        $this->app->instance(GetExpirablePositions::class, $mockUseCase);
        
        // Act & Assert
        $this->artisan(GetExpirablePositionsCommand::class)
            ->expectsOutput('2 positions listed.')
            ->assertExitCode(0);
    }

    public function test_zero_items(): void
    {
        // Arrange
        $mockUseCase = Mockery::mock(GetExpirablePositions::class);
        $mockUseCase->shouldReceive('handle')
            ->andReturn(Result::success([]));
        
        $this->app->instance(GetExpirablePositions::class, $mockUseCase);
        
        // Act & Assert
        $this->artisan(GetExpirablePositionsCommand::class)
            ->expectsOutput('No expired positions found.')
            ->assertExitCode(0);
    }
}
