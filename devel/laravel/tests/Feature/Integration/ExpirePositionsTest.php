<?php

namespace Tests\Feature\Integration;

use PHPUnit\Framework\Attributes\Test;
use Tests\Support\{
    ConfirmationsApiGivenWhenThen,
    DatabaseTestCase,
};

class ExpirePositionsTest extends DatabaseTestCase
{

    use ConfirmationsApiGivenWhenThen;

    #[Test]
    public function it_sets_position_quantity_to_zero_when_position_expires(): void
    {
        // GIVEN: an open option position

        $openingTrade = [
            'trade_number' => '001733',
            'transaction_date' => '2026-01-01',
            'security_number' => '7653ZG',
            'symbol' => 'SPX',
            'description' => "CALL-100 SPX'26 JAN@4245",
            'trade_action' => 'BUY',
            'position_effect' => 'OPEN',
            'trade_quantity' => 5,
            'unit_type' => 'CONTRACTS',
            'unit_price' => '1.00',
            'commission' => '0.00',
            'us_tax' => '0.00',
            'expiration_date' => '2026-01-15',
        ];

        $this->givenTradeData($openingTrade);
        $this->whenSubmittingTrades();
        $this->thenTheResponseIsSuccessful();

        // WHEN: positions are expired as of 2026-01-31
        $this->whenExpiringPositions('2026-01-31');

        // THEN: the position quantity should be zero

        $this->thenTheResponseIsSuccessful();
        $this->thenTheDatabaseContainsPositions([
            [
                'security_number' => '7653ZG',
                'symbol' => 'SPX',
                'position_quantity' => 0,
                'unit_type' => 'CONTRACTS',
            ],
        ]);

        $this->thenTheDatabaseContainsEvents([
            ['aggregate_type' => 'trade'],
        ]);
    }

    #[Test]
    public function it_creates_a_realized_gain_when_position_expires(): void
    {

        // GIVEN: an open option position which expires on 2026-01-15

        $openingTrade = [
            'trade_number' => '001733',
            'transaction_date' => '2026-01-01',
            'security_number' => '7653ZG',
            'symbol' => 'SPX',
            'description' => "CALL-100 SPX'26 JAN@4245",
            'trade_action' => 'BUY',
            'position_effect' => 'OPEN',
            'trade_quantity' => 5,
            'unit_type' => 'CONTRACTS',
            'unit_price' => '1.00',
            'commission' => '0.00',
            'us_tax' => '0.00',
            'expiration_date' => '2026-01-15',
        ];

        $this->givenTradeData($openingTrade);
        $this->whenSubmittingTrades();
        $this->thenTheResponseIsSuccessful();

        // WHEN: positions are expired as of 2026-01-31
        $this->whenExpiringPositions('2026-01-31');

        // THEN: a realized gain exists with realization source type = EXPIRATION
        $this->thenTheResponseIsSuccessful();
        $this->thenTheDatabaseContainsRealizedGains([
            [
                'security_number' => '7653ZG',
                'unit_type' => 'CONTRACTS',
                'realization_source_type' => 'EXPIRATION',
                'realization_source_reference' => '2026-01-15',
            ],
        ]);
    }
}
