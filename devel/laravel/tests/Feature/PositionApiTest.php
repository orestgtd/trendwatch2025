<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
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
            ->assertJson(['message' => 'There are no positions in Laravel.']);
    }
}
