<?php

namespace App\Presentation\Http\Controllers;

use App\Presentation\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class HelloController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(['message' => 'Hello from Laravel!']);
    }
}
