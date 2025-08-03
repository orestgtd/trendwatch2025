<?php

namespace App\Presentation\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PositionController
{
    public function index(Request $request): JsonResponse
    {
        // Placeholder: will eventually return filtered positions
        return response()->json([]);
    }
}
