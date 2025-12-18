<?php

namespace Tests\Support;

use Illuminate\Testing\TestResponse;

trait ConfirmationsApiGivenWhenThen
{
    protected array $tradePayload = [];
    protected TestResponse $response;

    /** ---------------- GIVEN ---------------- */

    protected function givenTradeData(array $trades): void
    {
        $this->tradePayload = $trades;
    }

    /** ---------------- WHEN ---------------- */

    protected function whenSubmittingTrades(): void
    {
        $this->response = $this->postJson('/api/beta/trades/import', $this->tradePayload);
    }

    /** ---------------- THEN ---------------- */

    protected function thenTheResponseIsSuccessful(): void
    {
        $this->response->assertStatus(200);
    }

    protected function thenTheResponseFails(int $code = 422): void
    {
        // Expect validation failure (adjust status if your app uses a different one)
        $this->response->assertStatus($code);
    }

    protected function thenTheDatabaseContainsTrades(array $expectedTrades): void
    {
        foreach ($expectedTrades as $trade) {
            $this->assertDatabaseHas('trades', $trade);
        }
    }

    protected function thenTheDatabaseDoesNotContainTrades(array $unexpectedTrades): void
    {
        foreach ($unexpectedTrades as $trade) {
            $this->assertDatabaseMissing('trades', $trade);
        }
    }

    protected function thenTheDatabaseContainsSecurities(array $expectedSecurities): void
    {
        foreach ($expectedSecurities as $security) {
            $this->assertDatabaseHas('securities', $security);
        }
    }

    protected function thenTheDatabaseContainsPositions(array $expectedPositions): void
    {
        foreach ($expectedPositions as $position) {
            $this->assertDatabaseHas('positions', $position);
        }
    }

    protected function thenTheDatabaseContainsRealizedGains(array $expectedRealizedGainBases): void
    {
        foreach ($expectedRealizedGainBases as $realizedGainBasis) {
            $this->assertDatabaseHas('realized_gain_basis', $realizedGainBasis);
        }
    }
}
