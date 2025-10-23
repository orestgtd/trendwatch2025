<?php

namespace Tests\Unit\Services;

use Mockery;
use Tests\TestCase;
use Tests\Unit\Support\{
    Builders\SecurityRequestBuilder,
    Factories\PersistedSecurityFactory,
};

use PHPUnit\Framework\Attributes\Test;

use App\Application\ProcessTradeConfirmation\{
    Services\SecurityService,
};

use App\Domain\Security\Outcome\NewSecurityCreated;
use App\Domain\Security\Outcome\VariationAdded;
use App\Infrastructure\Laravel\Eloquent\{
    Security\Repositories\EloquentSecurityRepository as SecurityRepository,
};

use App\Shared\Result;

class SecurityServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    #[Test]
    public function it_creates_a_new_security()
    {
        // Given
        $mockRepository = Mockery::mock(SecurityRepository::class);
        $this->app->instance(SecurityRepository::class, $mockRepository);
        $mockRepository
            ->shouldReceive('findBySecurityNumber')
            ->andReturn(
                null
            );

        /** @var SecurityService $service */
        $service = app(SecurityService::class);

        // When
        $result = $service->processSecurityRequest(
            SecurityRequestBuilder::YYZ()->build()
        );

        // Then
        $this->assertInstanceOf(Result::class, $result);
        $this->assertTrue($result->isSuccess());
        $this->assertInstanceOf(NewSecurityCreated::class, $result->getValue());
    }

    #[Test]
    public function it_records_a_variation()
    {
        // Given
        $mockRepository = Mockery::mock(SecurityRepository::class);
        $this->app->instance(SecurityRepository::class, $mockRepository);
        $mockRepository
            ->shouldReceive('findBySecurityNumber')
            ->andReturn(
                PersistedSecurityFactory::YYZ()
            );

        /** @var SecurityService $service */
        $service = app(SecurityService::class);

        // When
        $result = $service->processSecurityRequest(
            SecurityRequestBuilder::YYZ()
                ->withDescription('PROMISE OF ADVENTURE')
                ->build()
        );

        // Then
        $this->assertInstanceOf(Result::class, $result);
        $this->assertTrue($result->isSuccess());
        $this->assertInstanceOf(VariationAdded::class, $result->getValue());
    }
}
