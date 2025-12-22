<?php

namespace Tests\Unit\Support;

use Tests\TestCase;

use App\Domain\Kernel\Money\{
    Currency,
    MoneyAmount
};

use App\Infrastructure\Laravel\{
    Contracts\PositionRepository,
};

use Tests\Unit\Support\Helpers\MockObject;

class PositionTestCase extends TestCase
{
    /** GIVEN */

    // protected function givenPositionIsNotFoundInRepository()
    // {
    //     MockObject::mock(
    //         PositionRepository::class,
    //         'findBySecurityNumber',
    //         null
    //     );
    // }

    /** ASSERT */

    protected function assertCurrency(string $expected, Currency $currency): void
    {
        $this->assertEquals($expected, $currency->value());
    }

    protected function assertAmount(string $expected, MoneyAmount $amount): void
    {
        $this->assertEquals($expected, $amount->value());
    }
}

