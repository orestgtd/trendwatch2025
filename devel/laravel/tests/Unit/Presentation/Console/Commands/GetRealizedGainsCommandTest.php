<?php

namespace Tests\Unit\Presentation\Console\Commands;

use Tests\{
    Support\Builders\RealizedGainBasisBuilder,
    TestCase,
};

pest()->extend(TestCase::class);

it('lists realized gains', function() {

    // Arrange

    $gain = RealizedGainBasisBuilder::gainFromBuyToCloseLongPosition()
        ->buildPersistedDto();

    givenRealizedGainsFoundInRepository([
        $gain
    ]);

    // Act & Assert
    $this->artisan('list:realized_gains')
        ->expectsOutput('1 realized gains listed.')
        ->assertExitCode(0);
});
