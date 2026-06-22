<?php

namespace Tests\Feature\Integration\Console;

use Mockery;
use PHPUnit\Framework\Attributes\Test;

use App\Foundation\Date;

use App\Application\{
    Expiration\ExpirePositions,
    Expiration\ExpirePositionsResult,
};

use Tests\TestCase;

final class ExpirePositionsCommandTest extends TestCase
{
    #[Test]
    public function it_invokes_the_use_case_with_the_supplied_as_of_date()
    {
        $useCase = Mockery::mock(ExpirePositions::class);

        $useCase
            ->shouldReceive('handle')
            ->once()
            ->with(Mockery::on(
                fn (Date $date) => $date->equalTo('2026-01-31')
            ))
            ->andReturn(
                new ExpirePositionsResult(3)
            );

        $this->app->instance(
            ExpirePositions::class,
            $useCase
        );

        $this->artisan('positions:expire', [
            '--as-of' => '2026-01-31',
        ])
            ->expectsOutput('Expired 3 positions.')
            ->assertSuccessful();
    }
}
