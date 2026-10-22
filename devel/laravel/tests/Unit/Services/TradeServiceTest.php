<?php

namespace Tests\Unit\Services;

use Tests\{
    Support\Builders\TradeRequestBuilder,
};

use App\Application\{
    Procedures\ProcessTradeConfirmation\Services\TradeService,
};

use App\Domain\{
    Confirmation\Outcome\NewConfirmationCreated,
};

it('creates a new trade', function() {

    // Given a trade that is not already in the repository
    givenTradeIsNotFoundInRepository();

    // When we process a new trade

    /** @var TradeService $service */
    $service = app(TradeService::class);
    $result = $service->processConfirmationRequest(
        TradeRequestBuilder::YYZ()->build()
    );
    expect($result)->toBeSuccessfulResult();

    // Then a new trade confirmation is created
    expect($result->getValue())->toBeInstanceOf(NewConfirmationCreated::class);
});
