<?php

namespace Tests\Unit\Application\Position\Service;

use PHPUnit\Framework\Attributes\Test;

use App\Application\ProcessTradeConfirmation\{
    Services\PositionService,
};

use App\Domain\Position\{
    Model\ShortPosition,
    Outcome\DecreasedHolding,
    Outcome\IncreasedHolding,
    Outcome\NewPositionCreated,
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
 * it_creates_a_new_short_position_when_open_effect()
 * it_updates_an_existing_short_position_when_open_effect()
 * it_updates_an_existing_short_position_when_close_effect()
 */

class SellToOpenShortPositionTest extends PositionTestCase
{
    #[Test]
    public function it_creates_a_new_short_position_when_open_effect()
    {
        // Given position not found in repository.
        $this->givenPositionIsNotFoundInRepository();

        // When we sell to open shares

        /** @var SecurityService $service */
        $service = app(PositionService::class);

        $result = $service->computePositionOutcome(
            ConfirmationBuilder::sellToOpenShares()
            ->withQuantity(100)
            ->withUnitPrice('25')
            ->withCommission('5')
            ->withUsTax('1')
            ->build()
        );

        // Then the outcome should be a newly created short position 

        $this->assertInstanceOf(Result::class, $result);
        $this->assertTrue($result->isSuccess());
        $outcome = $result->getValue();
        $this->assertInstanceOf(NewPositionCreated::class, $outcome);

        /** @var ShortPosition $position */
        $position = $outcome->getPosition();
        $this->assertInstanceOf(ShortPosition::class, $position);

        // And the position quantity should have the correct value
        $this->assertEquals(100, $position->getPositionQuantity()->value());

        // And the total proceeds should be correctly calculated
        $totalProceeds = $position->getTotalProceeds();
        $this->assertAmount('2494.00', $totalProceeds->getAmount());
        $this->assertCurrency('USD', $totalProceeds->getCurrency());

        // And the total cost should be zero
        $totalCost = $position->getTotalCost();
        $this->assertAmount('0.00', $totalCost->getAmount());
        $this->assertCurrency('USD', $totalCost->getCurrency());
    }

    #[Test]
    public function it_updates_an_existing_short_position_when_open_effect()
    {
        // Given an existing position.
        MockObject::mock(
            PositionRepository::class,
            'findBySecurityNumber',
                PersistedPositionBuilder::YYZShort()
                    ->withQuantity(250)
                    ->withTotalProceeds('1000')
                    ->build()
        );

        // When we sell to open shares

        /** @var PositionService $service */
        $service = app(PositionService::class);

        $result = $service->computePositionOutcome(
            ConfirmationBuilder::sellToOpenShares()
            ->withQuantity(50)
            ->withUnitPrice('25')
            ->withCommission('5')
            ->withUsTax('1')
            ->build()
        );

        // Then the outcome should be an increased holding of a short position

        $this->assertInstanceOf(Result::class, $result);
        $this->assertTrue($result->isSuccess());
        $outcome = $result->getValue();
        $this->assertInstanceOf(IncreasedHolding::class, $outcome);

        /** @var ShortPosition $position */
        $position = $outcome->getPosition();
        $this->assertInstanceOf(ShortPosition::class, $position);

        // And the position quantity should have the correct value
        $this->assertEquals(300, $position->getPositionQuantity()->value());

        // And the total proceeds should be correctly calculated
        $totalProceeds = $position->getTotalProceeds();
        $this->assertAmount('2244.00', $totalProceeds->getAmount());
        $this->assertCurrency('USD', $totalProceeds->getCurrency());

        // And the total costs should still be zero
        $totalCost = $position->getTotalCost();
        $this->assertAmount('0.00', $totalCost->getAmount());
        $this->assertCurrency('USD', $totalCost->getCurrency());
    }

    #[Test]
    public function it_updates_an_existing_short_position_when_close_effect()
    {
        // Given an existing position.
        MockObject::mock(
            PositionRepository::class,
            'findBySecurityNumber',
                PersistedPositionBuilder::YYZShort()
                    ->withQuantity(100)
                    ->withTotalProceeds('1000.00')
                    ->build()
        );

        // When we buy to close shares

        /** @var PositionService $service */
        $service = app(PositionService::class);

        $result = $service->computePositionOutcome(
            ConfirmationBuilder::buyToCloseShares()
            ->withQuantity(25)
            ->withUnitPrice('4')
            ->withCommission('5')
            ->withUsTax('1')
            ->build()
        );

        // Then the outcome should be a decreased holding of a short position

        $this->assertInstanceOf(Result::class, $result);
        $this->assertTrue($result->isSuccess());
        $outcome = $result->getValue();
        $this->assertInstanceOf(DecreasedHolding::class, $outcome);

        /** @var ShortPosition $position */
        $position = $outcome->getPosition();
        $this->assertInstanceOf(ShortPosition::class, $position);

        // And the position quantity should have the correct value
        $this->assertEquals(75, $position->getPositionQuantity()->value());

        // And the total cost should be unchanged
        // $totalCost = $position->getTotalCost();
        // $this->assertAmount('1000.00', $totalCost->getAmount());
        // $this->assertCurrency('USD', $totalCost->getCurrency());

        // And the total proceeds should be calculated correctly
        // $totalProceeds = $position->getTotalProceeds();
        // $this->assertAmount('1494.00', $totalProceeds->getAmount());
        // $this->assertCurrency('USD', $totalProceeds->getCurrency());
    }

    private function givenPositionIsNotFoundInRepository()
    {
        MockObject::mock(
            PositionRepository::class,
            'findBySecurityNumber',
            null
        );
    }
}
