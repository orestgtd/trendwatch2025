<?php

namespace Tests\Unit\Services;

use Tests\{
    Support\Builders\SecurityRecordBuilder,
    Support\Builders\SecurityRequestBuilder,
};

use App\Application\Procedures\ProcessTradeConfirmation\{
    Services\SecurityService,
};

use App\Domain\{
    Security\Outcome\NewSecurityCreated,
    Security\Outcome\VariationAdded,
};

it('creates a new security', function() {

    // Given security does not already exist
    givenSecurityFoundInRepository(null);

    // When we process a security request

    /** @var SecurityService $service */
    $service = app(SecurityService::class);
    $result = $service->processSecurityRequest(
        SecurityRequestBuilder::YYZ()->build()
    );
    expect($result)->toBeSuccessfulResult();

    // Then a new security is created
    $outcome = $result->getValue();
    expect($outcome)->toBeInstanceOf(NewSecurityCreated::class);
});

it('records a variation', function() {

    // Given security already exists
    givenSecurityFoundInRepository(
        SecurityRecordBuilder::YYZ()
    );

    // When we process a security request with a new description

    /** @var SecurityService $service */
    $service = app(SecurityService::class);
    $result = $service->processSecurityRequest(
        SecurityRequestBuilder::YYZ()
            ->withDescription('PROMISE OF ADVENTURE')
            ->build()
    );
    expect($result)->toBeSuccessfulResult();

    // Then a new variation is added
    $outcome = $result->getValue();
    expect($outcome)->toBeInstanceOf(VariationAdded::class);
});
