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
use App\Presentation\ViewModels\RealizedGainRowViewModel;

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
            ->withRealizationDate('2026-09-30')
            ->withCost('28500.00')
            ->withProceeds('30250.00')
            ->buildPersistedDto();

        $mockRepository = Mockery::mock(RealizedGainRepositoryContract::class);
        $mockRepository->shouldReceive('all')
            ->once()
            ->andReturn([$gain]);

        $expectedHeader = RealizedGainRowViewModel::headers();

        $expectedBody = [
            [
                'MSFT',
                '2026-09-30',
                'LONG',
                '100',
                '100',
                'SHARES',
                'USD $28500.00',
                'USD $30250.00',
                'TRADE T0001',
            ],
        ];

        $this->app->instance(RealizedGainRepositoryContract::class, $mockRepository);

        // Act & Assert

        $this->artisan('list:realized_gains')
            ->expectsTable($expectedHeader, $expectedBody)
            ->expectsOutput('1 realized gains listed.')
            ->assertExitCode(0);
    }
}
