<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns a list of realized gains', function () {
    $response = $this->get('/api/beta/realized_gains');

    $response
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'security_number',
                    'realization_date',
                    'trade_number',
                    'base_quantity',
                    'trade_quantity',
                    'unit_type',
                    'cost',
                    'proceeds',
                ],
            ],
            'count',
        ]);
});
