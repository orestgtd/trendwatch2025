<?php

namespace App\Application\GetPositions;

use App\Application\GetPositions\Dto\PositionDto;
use App\Shared\Result;

final class GetPositions
{
    public function __construct(
        // In the future, you'll inject your position repository here
        // private PositionRepositoryInterface $positionRepository,
    ) {}

   /** @return Result<array> */
    public function handle(): Result
    {
        // Mock data for development
        $positions = [
            [
                'id' => 1,
                'security_number' => 'AAPL',
                'position_type' => 'LONG',
                'position_quantity' => 100,
                'total_cost' => 15000.00,
                'total_proceeds' => 0.00,
            ],
            [
                'id' => 2,
                'security_number' => 'GOOGL',
                'position_type' => 'LONG',
                'position_quantity' => 50,
                'total_cost' => 7500.50,
                'total_proceeds' => 0.00,
            ],
            [
                'id' => 3,
                'security_number' => 'TSLA',
                'position_type' => 'SHORT',
                'position_quantity' => 75,
                'total_cost' => 0.00,
                'total_proceeds' => 18750.25,
            ],
            [
                'id' => 4,
                'security_number' => 'MSFT',
                'position_type' => 'LONG',
                'position_quantity' => 200,
                'total_cost' => 60000.00,
                'total_proceeds' => 0.00,
            ],
            [
                'id' => 5,
                'security_number' => 'AMZN',
                'position_type' => 'LONG',
                'position_quantity' => 30,
                'total_cost' => 4500.75,
                'total_proceeds' => 0.00,
            ],
        ];

        return Result::success($positions);
    }
}
