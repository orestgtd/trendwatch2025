<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class PositionApiTest extends TestCase
{
    #[Test]
    public function it_returns_an_empty_response_for_positions()
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
