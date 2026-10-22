<?php

namespace Tests\Unit\Services;

use Tests\{
    Support\Builders\ConfirmationBuilder,
    Support\Builders\PositionRecordBuilder,
    TestCase,
};

use App\Application\Procedures\ProcessTradeConfirmation\{
    Services\PositionProcessor,
};

use App\Domain\Confirmation\{
    ValueObjects\CostAmount,
    ValueObjects\ProceedsAmount,
};

use App\Domain\Position\{
    Model\LongPosition,
    Model\ShortPosition,
    Outcome\DecreasedHolding,
    Outcome\IncreasedHolding,
    Outcome\NewPositionCreated,
    ValueObjects\PositionQuantity,
};

pest()->extend(TestCase::class);

it('creates a new long position when open effect', function() {

    // Given position not found in repository.
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
    expect($result)->toBeSuccessfulResult();

    // Then the outcome should be a newly created long position 

    $outcome = $result->getValue();
    expect($outcome)->toBeInstanceOf(NewPositionCreated::class);

    $position = $outcome->getPosition();
    expect($position)->toBeInstanceOf(LongPosition::class);

    // And the position quantity should have the correct value
    expect($position->getPositionQuantity())->toBeQuantity(
        PositionQuantity::class,
        100,
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

it('updates an existing long position when open effect', function() {

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
    expect($result)->toBeSuccessfulResult();

    // Then the outcome should be an increased holding of a long position

    $outcome = $result->getValue();
    expect($outcome)->toBeInstanceOf(IncreasedHolding::class);

    $position = $outcome->getPosition();
    expect($position)->toBeInstanceOf(LongPosition::class);

    // And the position quantity should have the correct value
    expect($position->getPositionQuantity())->toBeQuantity(
        PositionQuantity::class,
        300,
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

it('creates a new short position when open effect', function() {

    // Given position not found in repository.
    givenPositionIsNotFoundInRepository();

    // When we sell to open shares

    /** @var PositionProcessor $service */
    $service = app(PositionProcessor::class);
    $result = $service->computePositionOutcome(
        ConfirmationBuilder::sellToOpenShares()
        ->withQuantity(100)
        ->withUnitPrice('25')
        ->withCommission('5')
        ->withUsTax('1')
        ->build()
    );
    expect($result)->toBeSuccessfulResult();

    // Then the outcome should be a newly created short position 

    $outcome = $result->getValue();
    expect($outcome)->toBeInstanceOf(NewPositionCreated::class);

    $position = $outcome->getPosition();
    expect($position)->toBeInstanceOf(ShortPosition::class);

    // And the position quantity should have the correct value
    expect($position->getPositionQuantity())->toBeQuantity(
        PositionQuantity::class,
        100,
    );

    // And the total proceeds should be correctly calculated
    $totalProceeds = $position->getTotalProceeds();
    expect($totalProceeds)->toBeMonetary(
        ProceedsAmount::class,
        '2494.00', 'USD',
    );

    // And the total cost should be zero
    $totalCost = $position->getTotalCost();
    expect($totalCost)->toBeMonetary(
        CostAmount::class,
        '0.00', 'USD',
    );
});

it('updates an existing short position when open effect', function() {

    // Given an existing position.
    givenPositionFoundInRepository(
        PositionRecordBuilder::YYZShort()
            ->withQuantity(250)
            ->withTotalProceeds('1000')
            ->build()
    );

    // When we sell to open shares

    /** @var PositionProcessor $service */
    $service = app(PositionProcessor::class);
    $result = $service->computePositionOutcome(
        ConfirmationBuilder::sellToOpenShares()
        ->withQuantity(50)
        ->withUnitPrice('25')
        ->withCommission('5')
        ->withUsTax('1')
        ->build()
    );
    expect($result)->toBeSuccessfulResult();

    // Then the outcome should be an increased holding of a short position

    $outcome = $result->getValue();
    expect($outcome)->toBeInstanceOf(IncreasedHolding::class);

    $position = $outcome->getPosition();
    expect($position)->toBeInstanceOf(ShortPosition::class);

    // And the position quantity should have the correct value
    expect($position->getPositionQuantity())->toBeQuantity(
        PositionQuantity::class,
        300,
    );

    // And the total proceeds should be correctly calculated
    $totalProceeds = $position->getTotalProceeds();
    expect($totalProceeds)->toBeMonetary(
        ProceedsAmount::class,
        '2244.00', 'USD',
    );

    // And the total costs should still be zero
    $totalCost = $position->getTotalCost();
    expect($totalCost)->toBeMonetary(
        CostAmount::class,
        '0.00', 'USD',
    );
});

it('updates an existing long position when close effect', function() {

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

    // Then the outcome should be a decreased holding of a long position

    $outcome = $result->getValue();
    expect($outcome)->toBeInstanceOf(DecreasedHolding::class);

    $position = $outcome->getPosition();
    expect($position)->toBeInstanceOf(LongPosition::class);

    // And the position quantity should have the correct value
    expect($position->getPositionQuantity())->toBeQuantity(
        PositionQuantity::class,
        75,
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
    expect($result)->toBeSuccessfulResult();

    // Then the outcome should be a decreased holding of a short position

    $outcome = $result->getValue();
    expect($outcome)->toBeInstanceOf(DecreasedHolding::class);

    $position = $outcome->getPosition();
    expect($position)->toBeInstanceOf(ShortPosition::class);

    // And the position quantity should have the correct value
    expect($position->getPositionQuantity())->toBeQuantity(
        PositionQuantity::class,
        75,
    );

    // And the total cost should be calculated correctly
    $totalCost = $position->getTotalCost();
    expect($totalCost)->toBeMonetary(
        CostAmount::class,
        '106.00', 'USD',
    );

    // And the total proceeds should be unchanged
    $totalProceeds = $position->getTotalProceeds();
    expect($totalProceeds)->toBeMonetary(
        ProceedsAmount::class,
        '1000.00', 'USD',
    );
});

it('fails when given mixed trade types', function() {

    // Given an existing long position.
    givenPositionFoundInRepository(
        PositionRecordBuilder::YYZ()
            ->withQuantity(200)
            ->withTotalCost('2000')
            ->build()
    );

    // When we sell to add short units

    /** @var PositionProcessor $service */
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
    expect($result)->toBeFailureResult();
});
