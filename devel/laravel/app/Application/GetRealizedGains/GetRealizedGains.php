<?php

namespace App\Application\GetRealizedGains;

use App\Shared\Result;

final class GetRealizedGains
{
    public function __construct(
        // In the future, you'll inject your position repository here
        // private PositionRepositoryInterface $positionRepository,
    ) {}

   /** @return Result<array> */
    public function handle(): Result
    {
        // Mock data for development
        $realizedGains = [
            [
                'id' => 1,
                'security_number' => 'AAPL',
                'trade_number' => 'T-1001',
                'base_quantity' => 50,
                'trade_quantity' => 50,
                'unit_type' => 'SHARES',
                'cost' => 6500.00,
                'proceeds' => 7500.00,
            ],
            [
                'id' => 2,
                'security_number' => 'AAPL',
                'trade_number' => 'T-1002',
                'base_quantity' => 5,
                'trade_quantity' => 5,
                'unit_type' => 'CONTRACTS',
                'cost' => 1200.00,
                'proceeds' => 1350.00,
            ],
            [
                'id' => 3,
                'security_number' => 'GOOGL',
                'trade_number' => 'T-2001',
                'base_quantity' => 10,
                'trade_quantity' => 10,
                'unit_type' => 'SHARES',
                'cost' => 2800.50,
                'proceeds' => 3100.75,
            ],
            [
                'id' => 4,
                'security_number' => 'TSLA',
                'trade_number' => 'T-3001',
                'base_quantity' => 2,
                'trade_quantity' => 2,
                'unit_type' => 'CONTRACTS',
                'cost' => 4500.00,
                'proceeds' => 4100.25,
            ],
            [
                'id' => 5,
                'security_number' => 'TSLA',
                'trade_number' => 'T-3002',
                'base_quantity' => 40,
                'trade_quantity' => 40,
                'unit_type' => 'SHARES',
                'cost' => 9000.00,
                'proceeds' => 8200.25,
            ],
            [
                'id' => 6,
                'security_number' => 'MSFT',
                'trade_number' => 'T-4001',
                'base_quantity' => 100,
                'trade_quantity' => 100,
                'unit_type' => 'SHARES',
                'cost' => 28500.00,
                'proceeds' => 30250.00,
            ],
            [
                'id' => 7,
                'security_number' => 'MSFT',
                'trade_number' => 'T-4002',
                'base_quantity' => 10,
                'trade_quantity' => 10,
                'unit_type' => 'CONTRACTS',
                'cost' => 3200.00,
                'proceeds' => 3500.00,
            ],
        ];

        return Result::success($realizedGains);
    }
}
