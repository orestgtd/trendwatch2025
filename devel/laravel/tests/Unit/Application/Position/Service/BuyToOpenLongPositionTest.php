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
use App\Domain\Position\{
    Model\LongPosition,
    Outcome\IncreasedHolding,
    Outcome\NewPositionCreated,
};
use App\Domain\Position\ValueObjects\PositionQuantity;
use App\Domain\RealizedGain\{
    Outcome\NoRealizedGain,
};

/**
 * 
 * it_creates_a_new_long_position_when_open_effect()
 * it_updates_an_existing_long_position_when_open_effect()
 * 
 */

it('creates a new long position when open effect', function () {

    // Given position does not already exist
    givenPositionIsNotFoundInRepository();

    // When we buy to open shares

    /** @var PositionProcessor $service */
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

    expect($result)->toBeSuccessfulResult();

    $outcome = $result->getValue();
    expect($outcome)->toBeInstanceOf(NewPositionCreated::class);

    // This trade does not trigger a realized gain
    $realizedGainOutcome = $outcome->getRealizedGainOutcome();
    expect($realizedGainOutcome)->toBeInstanceOf(NoRealizedGain::class);

    $position = $outcome->getPosition();
    expect($position)->toBeInstanceOf(LongPosition::class);

    // And the position quantity should have the correct value
    expect($position->getPositionQuantity())->toBeQuantity(
        PositionQuantity::class,
        100
    );

    // And the total cost should be correctly calculated
    $totalCost = $position->getTotalCost();
    expect($totalCost)->toBeMonetary(
        CostAmount::class,
        '2506.00', 'USD',
    );

    // And the total proceeds should be zero
    $totalProceeds = $position->getTotalProceeds();
    expect($totalProceeds)->toBeMonetary(
        ProceedsAmount::class,
        '0.00', 'USD',
    );
});

it('updates an existing long position when open effect', function () {

    // Given an existing position.
    givenPositionFoundInRepository(
            PositionRecordBuilder::YYZ()
                ->withQuantity(200)
                ->withTotalCost('1000')
                ->build()
    );

    // When we buy to open shares

    /** @var PositionProcessor $service */
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

    expect($result)->toBeSuccessfulResult();

    $outcome = $result->getValue();
    expect($outcome)->toBeInstanceOf(IncreasedHolding::class);

    // This trade does not trigger a realized gain
    $realizedGainOutcome = $outcome->getRealizedGainOutcome();
    expect($realizedGainOutcome)->toBeInstanceOf(NoRealizedGain::class);

    $position = $outcome->getPosition();
    expect($position)->toBeInstanceOf(LongPosition::class);

    // And the position quantity should have the correct value
    expect($position->getPositionQuantity())->toBeQuantity(
        PositionQuantity::class,
        300
    );

    // And the total cost should be correctly calculated
    $totalCost = $position->getTotalCost();
    expect($totalCost)->toBeMonetary(
        CostAmount::class,
        '3506.00', 'USD',
    );

    // And the total proceeds should still be zero
    $totalProceeds = $position->getTotalProceeds();
    expect($totalProceeds)->toBeMonetary(
        ProceedsAmount::class,
        '0.00', 'USD',
    );
});
