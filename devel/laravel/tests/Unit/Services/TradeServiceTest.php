<?php

namespace Tests\Unit\Services;

use Mockery;
use Tests\TestCase;
use Tests\Unit\Factories\{
    TradeRequestBuilder,
};

use PHPUnit\Framework\Attributes\Test;

use App\Application\ProcessTradeConfirmation\{
    Services\TradeService,
};

use App\Domain\Confirmation\Outcome\NewConfirmationCreated;

use App\Infrastructure\Laravel\Eloquent\{
    Trade\Repositories\EloquentTradeRepository as TradeRepository,
};

use App\Shared\Result;

class TradeServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    #[Test]
    public function it_creates_a_new_trade()
    {
        // Given
        $mockRepository = Mockery::mock(TradeRepository::class);
        $this->app->instance(TradeRepository::class, $mockRepository);
        $mockRepository
            ->shouldReceive('findByTradeNumber')
            ->andReturn(
                null
            );

        /** @var TradeService $service */
        $service = app(TradeService::class);

        // When
        $result = $service->processConfirmationRequest(
            TradeRequestBuilder::YYZ()->build()
        );

        // Then
        $this->assertInstanceOf(Result::class, $result);
        $this->assertTrue($result->isSuccess());
        $this->assertInstanceOf(NewConfirmationCreated::class, $result->getValue());
    }
}
