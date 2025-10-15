<?php

namespace Tests\Unit\Services;

use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Unit\Factories\{
    ConfirmationFactory,
    PersistedPositionBuilder,
};

use App\Application\ProcessTradeConfirmation\{
    Services\PositionService,
};

use App\Domain\Position\Outcome\{
    IncreasedHolding,
    NewPositionCreated,
};

use App\Infrastructure\Laravel\Eloquent\{
    Position\Repositories\EloquentPositionRepository as PositionRepository,
};

use App\Shared\Result;

class PositionServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    #[Test]
    public function it_creates_a_new_position_when_open_effect()
    {
        // Given
        $mockRepository = Mockery::mock(PositionRepository::class);
        $this->app->instance(PositionRepository::class, $mockRepository);
        $mockRepository->shouldReceive('findBySecurityNumber')->andReturn(null);

        /** @var SecurityService $service */
        $service = app(PositionService::class);

        // When
        $result = $service->createOrUpdatePosition(
            ConfirmationFactory::T12345()
        );

        // Then
        $this->assertInstanceOf(Result::class, $result);
        $this->assertTrue($result->isSuccess());
        $this->assertInstanceOf(NewPositionCreated::class, $result->getValue());
    }

    #[Test]
    public function it_updates_an_existing_position_when_open_effect()
    {
        // Given
        $mockRepository = Mockery::mock(PositionRepository::class);
        $this->app->instance(PositionRepository::class, $mockRepository);
        $mockRepository
            ->shouldReceive('findBySecurityNumber')
            ->andReturn(
                PersistedPositionBuilder::YYZ()
                    ->withQuantity(200)
                    ->withTotalCost('100')
                    ->build()
            );

        /** @var PositionService $service */
        $service = app(PositionService::class);

        // When
        $result = $service->createOrUpdatePosition(
            ConfirmationFactory::T12346()
        );

        // Then
        $this->assertInstanceOf(Result::class, $result);
        $this->assertTrue($result->isSuccess());
        $this->assertInstanceOf(IncreasedHolding::class, $result->getValue());
    }

    // #[Test]
    // public function given_a_close_position_effect_when_creating_or_updating_position_then_the_existing_position_is_reduced_successfully()
    // {
    //     // Given
    //     $confirmation = new Confirmation(PositionEffect::CLOSE);
    //     $service = new PositionService();

    //     $expectedOutcome = new PositionOutcome('reduced');

    //     // When
    //     $result = $service->createOrUpdatePosition($confirmation);

    //     // Then
    //     $this->assertInstanceOf(Result::class, $result);
    //     $this->assertTrue($result->isSuccess());
    //     $this->assertInstanceOf(PositionOutcome::class, $result->value());
    //     $this->assertEquals($expectedOutcome->status(), $result->value()->status());
    // }

}
