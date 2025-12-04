<?php

namespace App\Domain\RealizedGain\Model;

use App\Domain\Security\ValueObjects\{
    SecurityNumber,
};

use App\Domain\Position\ValueObjects\{
    BaseQuantity,
};

use App\Domain\Confirmation\ValueObjects\{
    CostAmount,
    ProceedsAmount,
    TradeNumber,
    TradeQuantity,
};

final class RealizedGain
{
    private readonly SecurityNumber $securityNumber;
    private readonly TradeNumber $tradeNumber;
    private readonly BaseQuantity $baseQuantity;
    private readonly TradeQuantity $tradeQuantity;
    private readonly CostAmount $cost;
    private readonly ProceedsAmount $proceeds;

    public function getSecurityNumber(): SecurityNumber { return $this->securityNumber; }
    public function getTradeNumber(): TradeNumber { return $this->tradeNumber; }
    public function getBaseQuantity(): BaseQuantity { return $this->baseQuantity; }
    public function getTradeQuantity(): TradeQuantity { return $this->tradeQuantity; }
    public function getCost(): CostAmount { return $this->cost; }
    public function getProceeds(): ProceedsAmount { return $this->proceeds; }

    /*
            $table->string('trade_number');
            $table->integer('quantity');
            $table->string('cost_amount');
            $table->string('cost_currency');
            $table->string('proceeds_amount');
            $table->string('proceeds_currency');
    */


    private function __construct(
        SecurityNumber $securityNumber,
        TradeNumber $tradeNumber,
        BaseQuantity $baseQuantity,
        TradeQuantity $tradeQuantity,
        CostAmount $cost,
        ProceedsAmount $proceeds,
    ) {
        $this->securityNumber = $securityNumber;
        $this->tradeNumber = $tradeNumber;
        $this->baseQuantity = $baseQuantity;
        $this->tradeQuantity = $tradeQuantity;
        $this->cost = $cost;
        $this->proceeds = $proceeds;
    }

    public static function create(
        SecurityNumber $securityNumber,
        TradeNumber $tradeNumber,
        BaseQuantity $baseQuantity,
        TradeQuantity $tradeQuantity,
        CostAmount $cost,
        ProceedsAmount $proceeds,
    ): self {
        return new self(
            $securityNumber,
            $tradeNumber,
            $baseQuantity,
            $tradeQuantity,
            $cost,
            $proceeds,
        );
    }
}
