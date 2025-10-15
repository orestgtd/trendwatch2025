<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class HelloApiTest extends TestCase
{
    #[Test]
    public function it_returns_a_hello_message()
    {
        $response = $this->get('/api/beta/hello');

        $response
            ->assertStatus(200)
            ->assertJson(['message' => 'Hello from Laravel!']);
    }
}
