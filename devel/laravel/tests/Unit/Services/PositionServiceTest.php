<?php

namespace Tests\Unit\Services;

use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Unit\Support\{
    Builders\ConfirmationBuilder,
    Builders\PersistedPositionBuilder,
};

use App\Application\ProcessTradeConfirmation\{
    Services\PositionProcessor,
};

use App\Domain\Kernel\Money\{
    Currency,
    MoneyAmount
};

use App\Domain\Position\{
    Model\LongPosition,
    Model\ShortPosition,
    Outcome\DecreasedHolding,
    Outcome\IncreasedHolding,
    Outcome\NewPositionCreated,
};

use App\Infrastructure\Laravel\Eloquent\{
    Position\Repositories\EloquentPositionRepository as PositionRepository,
};

use App\Shared\Result;

class PositionServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    #[Test]
    public function it_creates_a_new_long_position_when_open_effect()
    {
        // Given position not found in repository.
        MockObject::mock(
            PositionRepository::class,
            'findBySecurityNumber',
            null
        );

        // When we buy to open shares

        /** @var SecurityService $service */
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

    #[Test]
    public function it_creates_a_new_short_position_when_open_effect()
    {
        // Given position not found in repository.
        MockObject::mock(
            PositionRepository::class,
            'findBySecurityNumber',
            null
        );

        // When we sell to open shares

        /** @var SecurityService $service */
        $service = app(PositionProcessor::class);

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
        $service = app(PositionProcessor::class);

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

        /** @var LongPosition $position */
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
    public function it_updates_an_existing_long_position_when_close_effect()
    {
        // Given an existing position.
        MockObject::mock(
            PositionRepository::class,
            'findBySecurityNumber',
                PersistedPositionBuilder::YYZ()
                    ->withQuantity(100)
                    ->withTotalCost('1000.00')
                    ->build()
        );

        // When we sell to close shares

        /** @var PositionService $service */
        $service = app(PositionProcessor::class);

        $result = $service->computePositionOutcome(
            ConfirmationBuilder::sellToCloseShares()
            ->withQuantity(25)
            ->withUnitPrice('60')
            ->withCommission('5')
            ->withUsTax('1')
            ->build()
        );

        // Then the outcome should be a decreased holding of a long position

        $this->assertInstanceOf(Result::class, $result);
        $this->assertTrue($result->isSuccess());
        $outcome = $result->getValue();
        $this->assertInstanceOf(DecreasedHolding::class, $outcome);

        /** @var LongPosition $position */
        $position = $outcome->getPosition();
        $this->assertInstanceOf(LongPosition::class, $position);

        // And the position quantity should have the correct value
        $this->assertEquals(75, $position->getPositionQuantity()->value());

        // And the total cost should be unchanged
        $totalCost = $position->getTotalCost();
        $this->assertAmount('1000.00', $totalCost->getAmount());
        $this->assertCurrency('USD', $totalCost->getCurrency());

        // And the total proceeds should be calculated correctly
        $totalProceeds = $position->getTotalProceeds();
        $this->assertAmount('1494.00', $totalProceeds->getAmount());
        $this->assertCurrency('USD', $totalProceeds->getCurrency());
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
        $service = app(PositionProcessor::class);

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


    #[Test]
    public function it_fails_when_given_mixed_trade_types()
    {
        // Given an existing long position.
        MockObject::mock(
            PositionRepository::class,
            'findBySecurityNumber',
                PersistedPositionBuilder::YYZ()
                    ->withQuantity(200)
                    ->withTotalCost('2000')
                    ->build()
        );

        // When we sell to add short units
        /** @var PositionService $service */
        $service = app(PositionProcessor::class);

        $result = $service->computePositionOutcome(
            ConfirmationBuilder::sellToOpenShares()
            ->withQuantity(50)
            ->withUnitPrice('4')
            ->withCommission('5')
            ->withUsTax('1')
            ->build()
        );

        // Then trade should fail
        $this->assertTrue($result->isFailure());
    }

    private function assertCurrency(string $expected, Currency $currency): void
    {
        $this->assertEquals($expected, $currency->value());
    }

    private function assertAmount(string $expected, MoneyAmount $amount): void
    {
        $this->assertEquals($expected, $amount->value());
    }
}

class MockObject
{
    public static function mock(string $objectName, string $methodName, mixed $returnValue): void
    {
        $mockRepository = Mockery::mock($objectName);
        app()->instance($objectName, $mockRepository);
        $mockRepository->shouldReceive($methodName)->andReturn($returnValue);
    }
}
