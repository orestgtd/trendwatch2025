<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Support\ConfirmationsApiGivenWhenThen;

use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreConfirmationTest extends TestCase
{
    use RefreshDatabase;
    use ConfirmationsApiGivenWhenThen;

    #[Test]
    public function it_imports_multiple_trades_successfully()
    {
        // GIVEN: first trade
        $trade1 = [
            'transaction_date' => '2022-05-16',
            'security_number' => '7653ZG',
            'symbol' => 'SPX',
            'description' => "CALL-100 SPX'22 JN@4245",
            'trade_action' => 'BUY',
            'quantity' => 1,
            'price' => 21,
            'unit_type' => 'CONTRACTS',
            'expiration_date' => '2022-06-10',
        ];

        // GIVEN: second trade
        $trade2 = [
            'transaction_date' => '2022-05-23',
            'security_number' => '151447',
            'symbol' => 'CVE',
            'description' => "CENOVUS ENERGY INC",
            'trade_action' => 'BUY',
            'quantity' => 200,
            'price' => 21.94,
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

        // // THEN: verify database contains both trades
        // $this->thenTheDatabaseContainsTrades([
        //     ['trade_number' => '001733', 'quantity' => 1],
        //     ['trade_number' => '333499', 'quantity' => 200],
        // ]);

        $this->thenTheDatabaseContainsSecurities([
            ['canonical_description' => 'CALL-100 SPX\'22 JN@4245'],
            ['canonical_description' => 'CENOVUS ENERGY INC'],
        ]);
    }
}
