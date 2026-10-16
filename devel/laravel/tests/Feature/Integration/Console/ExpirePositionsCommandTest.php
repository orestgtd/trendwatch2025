<?php

namespace Tests\Feature\Integration\Console;

use Mockery;

use App\Application\{
    Procedures\Expiration\ExpirePositions,
    Procedures\Expiration\ExpirePositionsResult,
};

use App\Foundation\{
    Date,
};

it(
    'invokes the use case with the supplied as of date',
    function() {
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
);
