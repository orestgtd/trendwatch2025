<?php

namespace Tests\Unit\Domain\Expiration;

use PHPUnit\Framework\Attributes\Test;

use App\Domain\{
    Expiration\Outcome\PositionExpired,
    Kernel\Values\ExpirationDate,
    Position\Model\ExpirablePosition,
    RealizedGain\Outcome\NewRealizedGainCreated,
    RealizedGain\ValueObjects\RealizationSourceType,
};

use App\Shared\Date;

use Tests\Unit\Support\{
    Builders\PositionBuilder,
    PositionTestCase,
};

final class ExpirePositionTest extends PositionTestCase
{
    #[Test]
    public function it_consumes_all_remaining_quantity_when_position_expires()
    {
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
        $this->assertInstanceOf(PositionExpired::class, $outcome);

        // And the position should have zero remaining quantity
        $this->assertEquals(0, $outcome->getPositionQuantity()->toInt());

        // And a realized gain should be produced
        $realizedGainOutcome = $outcome->getRealizedGainOutcome();
        $this->assertInstanceOf(NewRealizedGainCreated::class, $realizedGainOutcome);

        // And realization source type should be expiration
        $realizedGainBasis = $realizedGainOutcome->getRealizedGainBasis();
        $source = $realizedGainBasis->getRealizationSource();
        $this->assertEquals(RealizationSourceType::EXPIRATION, $source->getType());

        // And realization reference should be expiration date of position
        $reference = $source->getReference();
        $this->assertInstanceOf(ExpirationDate::class, $reference);
        $this->assertEquals($expirationDate, $reference->toDate());
    }
}
