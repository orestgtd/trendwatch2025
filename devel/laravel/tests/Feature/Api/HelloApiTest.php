<?php

it('returns a hello message', function () {
    $response = $this->get('/api/beta/hello');

    $response
        ->assertStatus(200)
        ->assertJson(['message' => 'Hello from Laravel!']);
});
