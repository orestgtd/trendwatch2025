<?php

namespace Tests\Unit\Application\Position\Service;

use Tests\{
    Support\Builders\ConfirmationBuilder,
    Support\Builders\PositionRecordBuilder,
};

use App\Application\{
    Procedures\ProcessTradeConfirmation\Services\PositionProcessor,
};

use App\Domain\{
    Confirmation\ValueObjects\CostAmount,
    Confirmation\ValueObjects\ProceedsAmount,
    Confirmation\ValueObjects\TradeQuantity,
    Position\Model\ShortPosition,
    Position\Outcome\DecreasedHolding,
    Position\ValueObjects\BaseQuantity,
    RealizedGain\Outcome\NewRealizedGainCreated,
};

/**
 * it_updates_an_existing_short_position_when_close_effect()
 * it_triggers_realized_gain_when_close_effect
 * it_preserves_existing_cost_when_rebuilding_from_persistence
 */

it('updates an existing short position when close effect', function() {

    // Given an existing position.
    givenPositionFoundInRepository(
        PositionRecordBuilder::YYZShort()
            ->withQuantity(100)
            ->withTotalProceeds('1000.00')
            ->build()
    );

    // When we buy to close shares

    /** @var PositionProcessor $service */
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
    // And the position quantity should have the correct value

    expect($result)->toBeSuccessfulResult();

    $outcome = $result->getValue(); 
    expect($outcome)->toBeInstanceOf(DecreasedHolding::class);

    $position = $outcome->getPosition();
    expect($position)
        ->toBeInstanceOf(ShortPosition::class)
        ->and($position->getPositionQuantity()->value())
        ->toBe(75);

    // And the total proceeds should be unchanged
    $totalProceeds = $position->getTotalProceeds();
    expect($totalProceeds)->toBeMonetary(
        ProceedsAmount::class,
        '1000.00', 'USD',
    );

    // And the total cost should be calculated correctly
    $totalCost = $position->getTotalCost();
    expect($totalCost)->toBeMonetary(
        CostAmount::class,
        '106.00', 'USD',
    );
});

it('triggers realized gain when close effect', function() {

    // Given an existing position.
    givenPositionFoundInRepository(
        PositionRecordBuilder::YYZShort()
            ->withQuantity(100)
            ->withTotalProceeds('1000.00')
            ->build()
    );

    // When we buy to close shares

    /** @var PositionProcessor $service */
    $service = app(PositionProcessor::class);
    $result = $service->computePositionOutcome(
        ConfirmationBuilder::buyToCloseShares()
        ->withQuantity(25)
        ->withUnitPrice('1')
        ->withCommission('1')
        ->withUsTax('0.02')
        ->build()
    );

    // Then trade should trigger a realized gain

    expect($result)->toBeSuccessfulResult();

    $outcome = $result->getValue();
    expect($outcome)->toBeInstanceOf(DecreasedHolding::class);

    $realizedGainOutcome = $outcome->getRealizedGainOutcome();
    expect($realizedGainOutcome)->toBeInstanceOf(NewRealizedGainCreated::class);

    $realizedGainBasis = $realizedGainOutcome->getRealizedGainBasis();
    expect($realizedGainBasis)->not()->toBeNull();

    /* Realized gain shoud have correct base quantity */
    expect($realizedGainBasis->getBaseQuantity())
        ->toBeQuantity(BaseQuantity::class, 100);

    /* And realized gain shoud have correct trade quantity */
    expect($realizedGainBasis->getTradeQuantity())
        ->toBeQuantity(TradeQuantity::class, 25);

    /* And realized gain shoud have correct cost */
    $cost = $realizedGainBasis->getCost();
    expect($cost)->toBeMonetary(
        CostAmount::class,
        '26.02', 'USD',
    );

    /* And realized gain shoud have correct proceeds */
    $proceeds = $realizedGainBasis->getProceeds();
    expect($proceeds)->toBeMonetary(
        ProceedsAmount::class,
        '1000.00', 'USD',
    );
});

it('preserves existing cost when rebuilding from persistence', function() {

    // Given an existing short position that already has cost from a previous cover
    givenPositionFoundInRepository(
        PositionRecordBuilder::YYZShort()
            ->withQuantity(75)
            ->withTotalProceeds('1000.00')
            ->withTotalCost('300.00')  // Already has cost!
            ->build()
    );

    // When we buy to cover more shares
    /** @var PositionProcessor $service */
    $service = app(PositionProcessor::class);
    $result = $service->computePositionOutcome(
        ConfirmationBuilder::buyToCloseShares()
        ->withQuantity(25)
        ->withUnitPrice('10')
        ->withCommission('5')
        ->withUsTax('1')
        ->build()
    );

    // Then the existing cost should be preserved and added to
    expect($result)->toBeSuccessfulResult();

    $outcome = $result->getValue();
    expect($outcome)->toBeInstanceOf(DecreasedHolding::class);

    $position = $outcome->getPosition();
    expect($position)->toBeInstanceOf(ShortPosition::class);

    // 300 (existing) + 256 (new cover: 25 * 10 + 5 + 1) = 556
    $totalCost = $position->getTotalCost();
    expect($totalCost)->toBeMonetary(
        CostAmount::class,
        '556.00', 'USD',
    );
});
