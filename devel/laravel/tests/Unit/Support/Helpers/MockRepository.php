<?php

namespace Tests\Unit\Support\Helpers;

use App\Application\{
    Contracts\PositionRepositoryContract,
    Contracts\RealizedGainRepositoryContract,
    Contracts\SecurityRepositoryContract,
    Contracts\TradeRepositoryContract,
};

class MockRepository
{
    use MockTrait;

    /**
     * Position
     */
    public static function findPositionBySecurityNumber(mixed $returnValue): void
    {
        static::mock(
            PositionRepositoryContract::class,
            'findBySecurityNumber',
            $returnValue
        );
    }

    /**
     * Trade
     */
    public static function findTradeByTradeNumber(mixed $returnValue): void
    {
        static::mock(
            TradeRepositoryContract::class,
            'findByTradeNumber',
            $returnValue
        );
    }

    /**
     * RealizedGain
     */
    public static function findAllRealizedGains(mixed $returnValue): void
    {
        static::mock(
            RealizedGainRepositoryContract::class,
            'all',
            $returnValue
        );
    }

    /**
     * Security
     */
    public static function findSecurityBySecurityNumber(mixed $returnValue): void
    {
        static::mock(
            SecurityRepositoryContract::class,
            'findBySecurityNumber',
            $returnValue
        );
    }
}
