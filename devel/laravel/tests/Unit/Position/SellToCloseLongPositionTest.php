<?php

namespace Tests\Unit\Position;

use PHPUnit\Framework\Attributes\Test;

use App\Application\ProcessTradeConfirmation\{
    Services\PositionService,
};

use App\Domain\Position\{
    Model\LongPosition,
    Outcome\DecreasedHolding,
};

use App\Domain\RealizedGain\{
    Model\RealizedGainBasis,
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
 * it_updates_an_existing_long_position_when_close_effect()
 * it_triggers_realized_gain_when_close_effect()
 * 
*/


class SellToCloseLongPositionTest extends PositionTestCase
{
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
        $service = app(PositionService::class);

        $result = $service->createOrUpdatePosition(
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
    public function it_triggers_realized_gain_when_close_effect()
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
        $service = app(PositionService::class);

        $result = $service->createOrUpdatePosition(
            ConfirmationBuilder::sellToCloseShares()
            ->withQuantity(25)
            ->withUnitPrice('60')
            ->withCommission('5')
            ->withUsTax('1')
            ->build()
        );

        // Then trade should trigger a realized gain
        $outcome = $this->assertResultIsDecreasedHolding($result);
        $outcome->tapRealizedGain(
            function (RealizedGainBasis $realizedGainBasis) {

                /* Realized gain shoud have correct base quantity */
                $this->assertEquals(100, $realizedGainBasis->getBaseQuantity()->value());

                /* And realized gain shoud have correct trade quantity */
                $this->assertEquals(25, $realizedGainBasis->getTradeQuantity()->value());

                /* And realized gain shoud have correct cost */
                $cost = $realizedGainBasis->getCost();
                $this->assertAmount('1000.00', $cost->getAmount());
                $this->assertCurrency('USD', $cost->getCurrency());

                /* And realized gain shoud have correct proceeds */
                $proceeds = $realizedGainBasis->getProceeds();
                $this->assertAmount('1494.00', $proceeds->getAmount());
                $this->assertCurrency('USD', $proceeds->getCurrency());
            }
        );
     }

    private function assertResultIsDecreasedHolding(Result $result): DecreasedHolding
    {
        $this->assertInstanceOf(Result::class, $result);
        $this->assertTrue($result->isSuccess());
        $outcome = $result->getValue();
        $this->assertInstanceOf(DecreasedHolding::class, $outcome);

        return $outcome;
    }
 }
