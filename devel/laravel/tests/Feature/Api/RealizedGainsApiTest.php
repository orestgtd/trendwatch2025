<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class RealizedGainsApiTest extends TestCase
{
    #[Test]
    public function it_returns_a_list_of_realized_gains()
    {
        $response = $this->get('/api/beta/realized_gains');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'security_number',
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
        }
}
