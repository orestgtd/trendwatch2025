<?php

namespace Tests\Feature\Confirmation;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Support\ConfirmationsApiGivenWhenThen;

use Illuminate\Foundation\Testing\RefreshDatabase;

class RecordDescriptionTest extends TestCase
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
            'price' => 21.94,
            'unit_type' => 'SHARES',
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
            'price' => 25.00,
            'unit_type' => 'SHARES',
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

        // THEN: verify database contains both trades
        // $this->thenTheDatabaseContainsTrades([
        //     ['trade_number' => '001733', 'trade_quantity' => 1],
        //     ['trade_number' => '333499', 'trade_quantity' => 200],
        // ]);

        $this->thenTheDatabaseContainsSecurities([
            ['canonical_description' => 'CENOVUS ENERGY INC'],
            ['variations' => json_encode(['CENOVUS ENERGY INCORPORATED'])],
        ]);
    }
}
