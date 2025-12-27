<?php

namespace Tests\Feature\Api;

use PHPUnit\Framework\Attributes\Test;

use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\Support\DatabaseTestCase;

class PositionApiTest extends DatabaseTestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_returns_a_list_of_positions()
    {
        $response = $this->get('/api/beta/positions');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'security_number', 'position_type', 'position_quantity', 'total_cost', 'total_proceeds']
                ],
                'count'
            ]);
        }
}
