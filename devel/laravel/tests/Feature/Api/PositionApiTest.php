<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns a list of positions', function () {
    $response = $this->get('/api/beta/positions');

    $response
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => ['id', 'security_number', 'position_type', 'position_quantity', 'total_cost', 'total_proceeds']
            ],
            'count'
        ]);
});
