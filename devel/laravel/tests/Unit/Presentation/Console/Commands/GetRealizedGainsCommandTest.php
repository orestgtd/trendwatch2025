<?php

namespace Tests\Unit\Presentation\Console\Commands;

use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\{
    Support\Builders\RealizedGainBasisBuilder,
    TestCase,
};

use App\Application\{
    Contracts\RealizedGainRepositoryContract,
};

class GetRealizedGainsCommandTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    #[Test]
    public function it_lists_realized_gains(): void
    {
        // Arrange

        $gain = RealizedGainBasisBuilder::gainFromBuyToCloseLongPosition()
            ->buildPersistedDto();

        $mockRepository = Mockery::mock(RealizedGainRepositoryContract::class);
        $mockRepository->shouldReceive('all')
            ->once()
            ->andReturn([$gain]);

        $this->app->instance(RealizedGainRepositoryContract::class, $mockRepository);

        // Act & Assert
        $this->artisan('list:realized_gains')
            ->expectsOutput('1 realized gains listed.')
            ->assertExitCode(0);
    }
}
