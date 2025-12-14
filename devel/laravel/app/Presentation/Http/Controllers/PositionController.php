<?php

namespace App\Presentation\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PositionController
{
    public function index(Request $request): JsonResponse
    {
        return $this->getPositions->handle()->match(
            onSuccess: fn(array $positions) => response()->json([
                'data' => $positions,
                'count' => count($positions)
            ]),
            onFailure: fn(string $error) => response()->json([
                'error' => $error
            ], 500)
        );
    }
}
