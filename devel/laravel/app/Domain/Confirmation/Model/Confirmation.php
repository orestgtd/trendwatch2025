<?php

namespace App\Domain\Confirmation\Model;

use App\Domain\Confirmation\ValueObjects\{
    TradeNumber,
};

use App\Domain\Security\ValueObjects\{
    SecurityNumber,
};

final class Confirmation
{
    private function __construct(
        private SecurityNumber $securityNumber,
        private TradeNumber $tradeNumber,
    ) {
        $this->securityNumber = $securityNumber;
        $this->tradeNumber = $tradeNumber;
    }

    public static function create(
        SecurityNumber $securityNumber,
        TradeNumber $tradeNumber,
    ): self {
        return new self(
            $securityNumber,
            $tradeNumber,
        );
    }

    public function getSecurityNumber(): SecurityNumber { return $this->securityNumber; }
    public function getTradeNumber(): TradeNumber { return $this->tradeNumber; }
}
