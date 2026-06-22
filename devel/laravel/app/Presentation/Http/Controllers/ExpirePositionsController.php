<?php

namespace App\Presentation\Http\Controllers;

use Illuminate\Http\{
    JsonResponse,
    Request,
};

use App\Application\Expiration\ExpirePositions;

use App\Foundation\Date;

class ExpirePositionsController
{
    public function __construct(
        private readonly ExpirePositions $usecase,
    ) {}

    public function store(Request $request): JsonResponse
    {
        $asOf = Date::fromString($request->asof);

        $result = $this->usecase->handle($asOf);

        return response()->json([
            'data' => $result,
        ]);
    }
}
