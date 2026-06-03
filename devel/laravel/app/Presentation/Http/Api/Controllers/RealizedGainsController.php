<?php

namespace App\Presentation\Http\Api\Controllers;

use Illuminate\Http\{
    JsonResponse,
};

use App\Application\{
    Reports\GetRealizedGains,
};

use App\Domain\{
    RealizedGain\Record\RealizedGainBasisRecord,
};

use App\Presentation\{
    Http\Presenters\RealizedGainPresenter,
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
            onSuccess: fn(array $records) => response()->json([
                'data' => array_map(
                    fn (RealizedGainBasisRecord $record) => RealizedGainPresenter::toArray($record),
                    $records
                ),
                'count' => count($records),
            ]),
            onFailure: fn(string $error) => response()->json([
                'error' => $error
            ], 500)
        );
    }

    /** @return Result<RealizedGainBasisRecord[]> */
    private function handleRealizedGains(): Result
    {
        return $this->getRealizedGains->handle();
    }
}
