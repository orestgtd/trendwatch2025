<?php

namespace App\Presentation\Http\Controllers;

use Illuminate\Http\{
    JsonResponse,
};

use App\Application\{
    GetRealizedGains\GetRealizedGains,
};

use App\Foundation\Result;

class RealizedGainsController
{
    public function __construct(
        private readonly GetRealizedGains $getRealizedGains
    ) {}

    public function index(): JsonResponse
    {
        return $this->handleRealizedGains()->match(
            onSuccess: fn(array $positions) => response()->json([
                'data' => $positions,
                'count' => count($positions)
            ]),
            onFailure: fn(string $error) => response()->json([
                'error' => $error
            ], 500)
        );
    }

    /** @return Result<array> */
    private function handleRealizedGains(): Result
    {
        return $this->getRealizedGains->handle();
    }
}
