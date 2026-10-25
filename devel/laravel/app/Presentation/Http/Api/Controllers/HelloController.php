<?php

namespace App\Presentation\Http\Api\Controllers;

use Illuminate\Http\JsonResponse;

class HelloController
{
    public function index(): JsonResponse
    {
        return response()->json(['message' => 'Hello from Laravel!']);
    }
}
