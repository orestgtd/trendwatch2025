<?php

namespace App\Presentation\Http\Controllers;

use Illuminate\Http\{
    JsonResponse,
};

use App\Application\{
    GetActivePositions\GetActivePositions,
};

class PositionController
{
    public function __construct(
        private readonly GetActivePositions $getPositions
    ) {}

    public function index(): JsonResponse
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
