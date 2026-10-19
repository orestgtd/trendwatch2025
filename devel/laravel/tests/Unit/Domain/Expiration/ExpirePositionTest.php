<?php

namespace Tests\Unit\Domain\Expiration;

use Tests\{
    Support\Builders\PositionBuilder,
};

use App\Domain\{
    Expiration\Outcome\PositionExpired,
    Kernel\Values\ExpirationDate,
    Position\ValueObjects\PositionQuantity,
    RealizedGain\Outcome\NewRealizedGainCreated,
    RealizedGain\ValueObjects\RealizationSourceType,
};

use App\Foundation\Date;

it('consumes all remaining quantity when position expires', function () {

    $expirationDate = Date::fromString('2026-01-15');
    $asOf = Date::fromString('2026-01-31');

    // Given an expired long position with remaining quantity
    $position = PositionBuilder::LongCall($expirationDate)
        ->withQuantity(5)
        ->withTotalCost('500.00')
        ->build();

    // When the position is expired
    $outcome = $position->expire($asOf);

    // Then the outcome should indicate position expired
    expect($outcome)->toBeInstanceOf(PositionExpired::class);

    // And the position should have zero remaining quantity
    expect($outcome->getPositionQuantity())->toBeQuantity(
        PositionQuantity::class,
        0,
    );

    // And a realized gain should be produced
    $realizedGainOutcome = $outcome->getRealizedGainOutcome();
    expect($realizedGainOutcome)->toBeInstanceOf(NewRealizedGainCreated::class);

    // And realization source type should be expiration
    $realizedGainBasis = $realizedGainOutcome->getRealizedGainBasis();
    $source = $realizedGainBasis->getRealizationSource();
    expect($source)->toBeRealizationSource(RealizationSourceType::EXPIRATION);

    // And realization reference should be expiration date of position
    $reference = $source->getReference();
    expect($reference)->toBeInstanceOf(ExpirationDate::class);
    expect($reference->toDate())->toEqual($expirationDate);
});
