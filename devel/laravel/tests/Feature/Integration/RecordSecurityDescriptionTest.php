<?php

namespace Tests\Feature\Integration;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Support\ConfirmationsApiGivenWhenThen;

use Illuminate\Foundation\Testing\RefreshDatabase;

class RecordSecurityDescriptionTest extends TestCase
{
    use RefreshDatabase;
    use ConfirmationsApiGivenWhenThen;

    #[Test]
    public function it_records_variations_successfully()
    {
        // GIVEN: first trade
        $trade1 = [
            'trade_number' => '001733',
            'transaction_date' => '2022-05-23',
            'security_number' => '151447',
            'symbol' => 'CVE',
            'description' => "CENOVUS ENERGY INC",
            'trade_action' => 'BUY',
            'position_effect' => 'OPEN',
            'trade_quantity' => 200,
            'unit_type' => 'SHARES',
            'unit_price' => '21.94',
            'commission' => '4.99',
            'us_tax' => '0.00',
            'expiration_date' => null,
        ];

        // GIVEN: second trade
        $trade2 = [
            'trade_number' => '333499',
            'transaction_date' => '2022-05-30',
            'security_number' => '151447',
            'symbol' => 'CVE',
            'description' => "CENOVUS ENERGY INCORPORATED",
            'trade_action' => 'BUY',
            'position_effect' => 'OPEN',
            'trade_quantity' => 100,
            'unit_type' => 'SHARES',
            'unit_price' => '25.00',
            'commission' => '1.99',
            'us_tax' => '0.80',
            'expiration_date' => null,
        ];

        // WHEN & THEN: submit first trade
        $this->givenTradeData($trade1);
        $this->whenSubmittingTrades();
        $this->thenTheResponseIsSuccessful('First trade failed: ' . $trade1['symbol']);

        // WHEN & THEN: submit second trade
        $this->givenTradeData($trade2);
        $this->whenSubmittingTrades();
        $this->thenTheResponseIsSuccessful('Second trade failed: ' . $trade2['symbol']);

        $this->thenTheDatabaseContainsSecurities([
            [
                'canonical_description' => 'CENOVUS ENERGY INC',
                'unit_type' => 'SHARES',
            ],
            [
                'variations' => json_encode(['CENOVUS ENERGY INCORPORATED']),
                'unit_type' => 'SHARES',
            ],
        ]);
    }
}
