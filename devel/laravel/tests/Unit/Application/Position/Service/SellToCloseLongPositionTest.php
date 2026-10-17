<?php

namespace Tests\Unit\Application\Position\Service;

use Tests\{
    Support\Builders\ConfirmationBuilder,
    Support\Builders\PositionRecordBuilder,
};

use App\Application\Procedures\ProcessTradeConfirmation\{
    Services\PositionProcessor,
};
use App\Domain\Confirmation\ValueObjects\CostAmount;
use App\Domain\Confirmation\ValueObjects\ProceedsAmount;
use App\Domain\Confirmation\ValueObjects\TradeQuantity;
use App\Domain\Position\{
    Model\LongPosition,
    Outcome\DecreasedHolding,
};
use App\Domain\Position\ValueObjects\BaseQuantity;
use App\Domain\Position\ValueObjects\PositionQuantity;
use App\Domain\RealizedGain\{
    Outcome\NewRealizedGainCreated,
};

/**
 * 
 * it_updates_an_existing_long_position_when_close_effect()
 * it_triggers_realized_gain_when_close_effect()
 * 
*/


it('updates an existing long position when close effect', function () {

    // Given an existing position.
    givenPositionFoundInRepository(
        PositionRecordBuilder::YYZ()
            ->withQuantity(100)
            ->withTotalCost('1000.00')
            ->build()
    );

    // When we sell to close shares

    /** @var PositionProcessor $service */
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

    expect($result)->toBeSuccessfulResult();

    $outcome = $result->getValue();
    expect($outcome)->toBeInstanceOf(DecreasedHolding::class);

    $position = $outcome->getPosition();
    expect($position)->toBeInstanceOf(LongPosition::class);

    // And the position quantity should have the correct value
    expect($position->getPositionQuantity())->toBeQuantity(
        PositionQuantity::class,
        75
    );

    // And the total cost should be unchanged
    $totalCost = $position->getTotalCost();
    expect($totalCost)->toBeMonetary(
        CostAmount::class,
        '1000.00', 'USD',
    );

    // And the total proceeds should be calculated correctly
    $totalProceeds = $position->getTotalProceeds();
    expect($totalProceeds)->toBeMonetary(
        ProceedsAmount::class,
        '1494.00', 'USD',
    );
});

it('triggers realized gain when close effect', function () {

    // Given an existing position.
    givenPositionFoundInRepository(
        PositionRecordBuilder::YYZ()
            ->withQuantity(100)
            ->withTotalCost('1000.00')
            ->build()
    );

    // When we sell to close shares

    /** @var PositionProcessor $service */
    $service = app(PositionProcessor::class);
    $result = $service->computePositionOutcome(
        ConfirmationBuilder::sellToCloseShares()
        ->withQuantity(25)
        ->withUnitPrice('60')
        ->withCommission('5')
        ->withUsTax('1')
        ->build()
    );
    expect($result)->toBeSuccessfulResult();

    // Then trade should trigger a realized gain
    $outcome = $result->getValue();
    expect($outcome)->toBeInstanceOf(DecreasedHolding::class);

    $realizedGainOutcome = $outcome->getRealizedGainOutcome();
    expect($realizedGainOutcome)->toBeInstanceOf(NewRealizedGainCreated::class);

    $realizedGainBasis = $realizedGainOutcome->getRealizedGainBasis();
    expect($realizedGainBasis)->not()->toBeNull();

    /* Realized gain should have correct base quantity */
    expect($realizedGainBasis->getBaseQuantity())->toBeQuantity(
        BaseQuantity::class,
        100,
    );

    /* And realized gain should have correct trade quantity */
    expect($realizedGainBasis->getTradeQuantity())->toBeQuantity(
        TradeQuantity::class,
        25,
    );

    /* And realized gain shoud have correct cost */
    $cost = $realizedGainBasis->getCost();
    expect($cost)->toBeMonetary(
        CostAmount::class,
        '1000.00', 'USD',
    );

    /* And realized gain shoud have correct proceeds */
    $proceeds = $realizedGainBasis->getProceeds();
    expect($proceeds)->toBeMonetary(
        ProceedsAmount::class,
        '1494.00', 'USD',
    );
});

it('preserves existing proceeds when rebuilding from persistence', function () {

    // Given an existing position that already has proceeds from a previous sale
    givenPositionFoundInRepository(
        PositionRecordBuilder::YYZ()
            ->withQuantity(75)
            ->withTotalCost('1000.00')
            ->withTotalProceeds('500.00')  // Already has proceeds!
            ->build()
    );

    // When we sell more shares
    /** @var PositionProcessor $service */
    $service = app(PositionProcessor::class);
    $result = $service->computePositionOutcome(
        ConfirmationBuilder::sellToCloseShares()
        ->withQuantity(25)
        ->withUnitPrice('20')
        ->withCommission('5')
        ->withUsTax('1')
        ->build()
    );
    expect($result)->toBeSuccessfulResult();

    // Then the existing proceeds should be preserved and added to
    $outcome = $result->getValue();
    expect($outcome)->toBeInstanceOf(DecreasedHolding::class);

    $position = $outcome->getPosition();
    $totalProceeds = $position->getTotalProceeds();
    
    // 500 (existing) + 494 (new sale: 25 * 20 - 5 - 1) = 994
    expect($totalProceeds)->toBeMonetary(
        ProceedsAmount::class,
        '994.00', 'USD',
    );
});
