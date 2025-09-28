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
            'trade_number' => '001733',
            'transaction_date' => '2022-05-16',
            'security_number' => '7653ZG',
            'symbol' => 'SPX',
            'description' => "CALL-100 SPX'22 JN@4245",
            'trade_action' => 'BUY',
            'position_effect' => 'OPEN',
            'trade_quantity' => 1,
            'unit_type' => 'CONTRACTS',
            'unit_price' => '21',
            'commission' => '9.99',
            'us_tax' => '0.02',
            'expiration_date' => '2022-06-10',
        ];

        // GIVEN: second trade
        $trade2 = [
            'trade_number' => '333499',
            'transaction_date' => '2022-05-23',
            'security_number' => '151447',
            'symbol' => 'CVE',
            'description' => "CENOVUS ENERGY INC",
            'trade_action' => 'BUY',
            'position_effect' => 'OPEN',
            'trade_quantity' => 200,
            'unit_type' => 'SHARES',
            'unit_price' => '21.94',
            'commission' => '12.40',
            'us_tax' => '0.49',
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
        $this->thenTheDatabaseContainsTrades([
            [
                'trade_number' => '001733', 'security_number' => '7653ZG',
                'trade_action' => 'BUY', 'position_effect' => 'OPEN',
                'trade_quantity' => 1,
                'unit_price_amount' => '21', 'unit_price_currency' => 'USD',
                'commission_amount' => '9.99', 'commission_currency' => 'USD',
                'us_tax_amount' => '0.02', 'us_tax_currency' => 'USD',
            ],
            [
                'trade_number' => '333499', 'security_number' => '151447',
                'trade_action' => 'BUY', 'position_effect' => 'OPEN',
                'trade_quantity' => 200,
                'unit_price_amount' => '21.94', 'unit_price_currency' => 'USD',
                'commission_amount' => '12.40', 'commission_currency' => 'USD',
                'us_tax_amount' => '0.49', 'us_tax_currency' => 'USD',
            ],
        ]);

        $this->thenTheDatabaseContainsSecurities([
            ['canonical_description' => 'CALL-100 SPX\'22 JN@4245'],
            ['canonical_description' => 'CENOVUS ENERGY INC'],
        ]);
    }

    #[Test]
    public function it_fails_when_inserting_duplicate_trade_number()
    {
        // GIVEN: first trade
        $trade1 = [
            'trade_number' => '001733',
            'transaction_date' => '2022-05-16',
            'security_number' => '7653ZG',
            'symbol' => 'SPX',
            'description' => "CALL-100 SPX'22 JN@4245",
            'trade_action' => 'BUY',
            'position_effect' => 'OPEN',
            'trade_quantity' => 1,
            'unit_type' => 'CONTRACTS',
            'unit_price' => '21',
            'commission' => '9.99',
            'us_tax' => '0.00',
            'expiration_date' => '2022-06-10',
        ];

        // GIVEN: second trade with same trade number as first
        $trade2 = [
            'trade_number' => '001733', // duplicate trade number
            'transaction_date' => '2022-05-23',
            'security_number' => '151447',
            'symbol' => 'CVE',
            'description' => "CENOVUS ENERGY INC",
            'trade_action' => 'BUY',
            'position_effect' => 'OPEN',
            'trade_quantity' => 200,
            'unit_type' => 'SHARES',
            'unit_price' => '21.94',
            'commission' => '1.99',
            'us_tax' => '0.50',
            'expiration_date' => null,
        ];

        // WHEN & THEN: submit first trade
        $this->givenTradeData($trade1);
        $this->whenSubmittingTrades();
        $this->thenTheResponseIsSuccessful('First trade failed: ' . $trade1['symbol']);

        // WHEN & THEN: submit second trade (duplicate)
        $this->givenTradeData($trade2);
        $this->whenSubmittingTrades();
        $this->thenTheResponseFails(422, 'Duplicate trade number should fail: ' . $trade2['symbol']);

        // THEN: verify database contains only the first trade
        $this->thenTheDatabaseContainsTrades([
            [
                'trade_number' => '001733', 
                'security_number' => '7653ZG',
                'trade_action' => 'BUY', 
                'position_effect' => 'OPEN',
                'trade_quantity' => 1, 
                'unit_price_amount' => '21', 
                'unit_price_currency' => 'USD',
                'commission_amount' => '9.99',
                'commission_currency' => 'USD',
                'us_tax_amount' => '0.00',
                'us_tax_currency' => 'USD',
            ],
        ]);

        $this->thenTheDatabaseDoesNotContainTrades([
            [
                'trade_number' => '001733',
                'security_number' => '151447',
            ]
        ]);
    }


}
