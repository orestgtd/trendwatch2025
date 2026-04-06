<?php

namespace App\Presentation\Http\Api\Controllers;

use Illuminate\Http\{
    JsonResponse,
};

use App\Application\{
    Reports\GetActivePositions,
};

use App\Domain\{
    Position\Model\Position,
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
                'data' => $this->mapPositions($positions),
                'count' => count($positions),
            ]),
            onFailure: fn(string $error) => response()->json([
                'error' => $error
            ], 500)
        );
    }

    /**
     * @param Position[] $positions
     * @return array<array<string, string>>
     */
    private function mapPositions(array $positions): array
    {
        return array_map(
            fn (Position $p) => $this->mapPosition($p),
            $positions
        );
    }

    /** @return array<string, string> */
    private function mapPosition(Position $position): array
    {
        return [
            'security_number' => (string) $position->getSecurityNumber(),
            'symbol' => (string) $position->getSymbol(),
            'position_type' => (string) $position->getPositionType(),
            'quantity' => (string) $position->getPositionQuantity(),
            'cost' => (string) $position->getTotalCost(),
            'proceeds' => (string) $position->getTotalProceeds(),
            'expiration_date' => (string) $position->getExpirationDate(),
        ];
    }
}
