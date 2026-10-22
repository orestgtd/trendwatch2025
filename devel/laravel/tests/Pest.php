<?php

use Tests\{
    TestCase,
    Unit\Support\Helpers\MockHandler,
    Unit\Support\Helpers\MockRepository,
};

use App\Domain\{
    Kernel\Money\Monetary,
    RealizedGain\ValueObjects\RealizationSource,
    Support\ValueObjects\Abstract\AbstractIntValueObject,
};

use App\Foundation\{
    Result,
};

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind different classes or traits.
|
*/

pest()->extend(TestCase::class)
    ->in('Feature');

uses(Tests\Unit\Support\PositionTestCase::class)
    ->in('Unit/Application/Position/Service');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

/**
 * @template T
 * @method static toBeSuccessfulResult(Result<T>)
 */
expect()->extend('toBeSuccessfulResult', function () {
    $result = $this->value;

    expect($result)->toBeInstanceOf(Result::class);

    /** @var Result $result */
    expect($result->isSuccess())->toBeTrue();

    return $this;
});

/**
 * @template T
 * @method static toBeFailureResult(Result<T>)
 */
expect()->extend('toBeFailureResult', function () {
    $result = $this->value;

    expect($result)->toBeInstanceOf(Result::class);

    /** @var Result $result */
    expect($result->isFailure())->toBeTrue();

    return $this;
});

/** @method static toBeMonetary(string $className, string $moneyAmount, string $currency) */
expect()->extend('toBeMonetary', function (
    string $expectedClassname,
    string $expectedMoneyAmount,
    string $expectedCurrency
) {
    $monetary = $this->value;

    expect($monetary)->toBeInstanceOf(Monetary::class);
    expect($monetary)->toBeInstanceOf($expectedClassname);

    /** @var Monetary $monetary */
    expect($monetary->getAmount()->value())->toBe($expectedMoneyAmount);
    expect($monetary->getCurrency()->value())->toBe($expectedCurrency);

    return $this;
});

/** @method static toBeQuantity(string $classname, int $quantity) */
expect()->extend('toBeQuantity', function (
    string $expectedClassname,
    int $expectedQuantity,
) {
    $quantity = $this->value;

    expect($quantity)->toBeInstanceOf(AbstractIntValueObject::class);
    expect($quantity)->toBeInstanceOf($expectedClassname);

    /** @var AbstractIntValueObject $quantity */
    expect($quantity->toInt())->toBe($expectedQuantity);

    return $this;
});

/** @method static toBeRealizationSource(string $sourceType) */
expect()->extend('toBeRealizationSource', function (
    string $expectedSourceType,
) {
    $source = $this->value;

    /** @var RealizationSource $source */
    expect($source)->toBeInstanceOf(RealizationSource::class);
    expect($source->getType()->value())->toBe($expectedSourceType);

    return $this;
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function givenExpirablePositionHandles(mixed $result): void
{
    MockHandler::handleExpirablePositions($result);
}

function givenPositionFoundInRepository(mixed $returnValue): void
{
    MockRepository::findPositionBySecurityNumber($returnValue);
}

function givenPositionIsNotFoundInRepository(): void
{
    MockRepository::findPositionBySecurityNumber(null);
}

function givenTradeIsNotFoundInRepository(): void
{
    MockRepository::findTradeByTradeNumber(null);
}

function givenRealizedGainsFoundInRepository(mixed $returnValue): void
{
    MockRepository::findAllRealizedGains($returnValue);
}

function givenSecurityFoundInRepository(mixed $returnValue): void
{
    MockRepository::findSecurityBySecurityNumber($returnValue);
}
