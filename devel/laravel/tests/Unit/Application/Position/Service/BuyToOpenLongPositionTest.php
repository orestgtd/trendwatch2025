<?php

namespace Tests\Unit\Application\Position\Service;

use PHPUnit\Framework\Attributes\Test;

use App\Application\ProcessTradeConfirmation\{
    Services\PositionProcessor,
};

use App\Domain\Position\{
    Model\LongPosition,
    Outcome\IncreasedHolding,
    Outcome\NewPositionCreated,
};

use App\Domain\RealizedGain\{
    Outcome\NoRealizedGain,
};

use App\Infrastructure\Laravel\Eloquent\{
    Position\Repositories\EloquentPositionRepository as PositionRepository,
};

use App\Shared\Result;

use Tests\Unit\Support\{
    Builders\ConfirmationBuilder,
    Builders\PersistedPositionBuilder,
    Helpers\MockObject,
    PositionTestCase,
};

/**
 * 
 * it_creates_a_new_long_position_when_open_effect()
 * it_updates_an_existing_long_position_when_open_effect()
 * 
 */

final class BuyToOpenLongPositionTest extends PositionTestCase
{
    #[Test]
    public function it_creates_a_new_long_position_when_open_effect()
    {
        // Given position does not already exist
        MockObject::mock(
            PositionRepository::class,
            'findBySecurityNumber',
            null
        );

        // When we buy to open shares

        /** @var PositionService $service */
        $service = app(PositionProcessor::class);

        $result = $service->computePositionOutcome(
            ConfirmationBuilder::BuyToOpenShares()
            ->withQuantity(100)
            ->withUnitPrice('25')
            ->withCommission('5')
            ->withUsTax('1')
            ->build()
        );

        // Then the outcome should be a newly created long position 

        $this->assertInstanceOf(Result::class, $result);
        $this->assertTrue($result->isSuccess());
        $outcome = $result->getValue();
        $this->assertInstanceOf(NewPositionCreated::class, $outcome);

        // This trade does not trigger a realized gain
        $realizedGainOutcome = $outcome->getRealizedGainOutcome();
        $this->assertInstanceOf(NoRealizedGain::class, $realizedGainOutcome);

        /** @var LongPosition $position */
        $position = $outcome->getPosition();
        $this->assertInstanceOf(LongPosition::class, $position);

        // And the position quantity should have the correct value
        $this->assertEquals(100, $position->getPositionQuantity()->value());

        // And the total cost should be correctly calculated
        $totalCost = $position->getTotalCost();
        $this->assertAmount('2506.00', $totalCost->getAmount());
        $this->assertCurrency('USD', $totalCost->getCurrency());

        // And the total proceeds should be zero
        $totalProceeds = $position->getTotalProceeds();
        $this->assertAmount('0.00', $totalProceeds->getAmount());
        $this->assertCurrency('USD', $totalProceeds->getCurrency());
    }

    #[Test]
    public function it_updates_an_existing_long_position_when_open_effect()
    {
        // Given an existing position.
        MockObject::mock(
            PositionRepository::class,
            'findBySecurityNumber',
                PersistedPositionBuilder::YYZ()
                    ->withQuantity(200)
                    ->withTotalCost('1000')
                    ->build()
        );

        // When we buy to open shares

        /** @var PositionService $service */
        $service = app(PositionProcessor::class);

        $result = $service->computePositionOutcome(
            ConfirmationBuilder::buyToOpenShares()
            ->withQuantity(100)
            ->withUnitPrice('25')
            ->withCommission('5')
            ->withUsTax('1')
            ->build()
        );

        // Then the outcome should be an increased holding of a long position

        $this->assertInstanceOf(Result::class, $result);
        $this->assertTrue($result->isSuccess());
        $outcome = $result->getValue();
        $this->assertInstanceOf(IncreasedHolding::class, $outcome);

        // This trade does not trigger a realized gain
        $realizedGainOutcome = $outcome->getRealizedGainOutcome();
        $this->assertInstanceOf(NoRealizedGain::class, $realizedGainOutcome);

        /** @var LongPosition $position */
        $position = $outcome->getPosition();
        $this->assertInstanceOf(LongPosition::class, $position);

        // And the position quantity should have the correct value
        $this->assertEquals(300, $position->getPositionQuantity()->value());

        // And the total cost should be correctly calculated
        $totalCost = $position->getTotalCost();
        $this->assertAmount('3506.00', $totalCost->getAmount());
        $this->assertCurrency('USD', $totalCost->getCurrency());

        // And the total proceeds should still be zero
        $totalProceeds = $position->getTotalProceeds();
        $this->assertAmount('0.00', $totalProceeds->getAmount());
        $this->assertCurrency('USD', $totalProceeds->getCurrency());
    }
}
