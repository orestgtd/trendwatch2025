<?php

namespace App\Domain\Position\Model;

use App\Domain\Confirmation\ValueObjects\{
    CostAmount,
    ProceedsAmount,
    TradeQuantity,
};

use App\Domain\Position\{
    Model\AbstractPosition,
    ValueObjects\BaseQuantity,
    ValueObjects\CostBase,
    ValueObjects\PositionType,
    ValueObjects\PositionQuantity,
};

use App\Domain\Security\{
    ValueObjects\SecurityNumber,
};


final class LongPosition extends AbstractPosition
{
    private CostBase $costBase;

    private function __construct(
        SecurityNumber $securityNumber,
        PositionQuantity $positionQuantity,
        CostAmount $totalCost,
    ) {
        $this->securityNumber = $securityNumber;
        $this->costBase = CostBase::create(
            BaseQuantity::fromPositionQuantity($positionQuantity),
            $totalCost,
        );
    }

    public static function create(
        SecurityNumber $securityNumber,
        PositionQuantity $positionQuantity,
        CostAmount $totalCost,
    ): self {
        return new self(
            $securityNumber,
            $positionQuantity,
            $totalCost,
        );
    }

    public function getPositionType(): PositionType
    {
        return PositionType::long();
    }

    public function getBaseQuantity(): BaseQuantity
    {
        return $this->costBase->getQuantity();
    }

    public function getPositionQuantity(): PositionQuantity
    {
        return PositionQuantity::fromBaseQuantity(
            $this->costBase->getQuantity()
        );
    }

    public function getTotalCost(): CostAmount
    {
        return $this->costBase->getTotalCost();
    }

    public function getTotalProceeds(): ProceedsAmount
    {
        return $this->costBase->getTotalProceeds();
    }

    public function addPurchase(TradeQuantity $change, CostAmount $tradeCost): static
    {
        $this->costBase->addPurchase($change, $tradeCost);

        return $this;
    }

    public function addSale(TradeQuantity $tradeQuantity, ProceedsAmount $tradeProceeds): self
    {
        $this->costBase->addSale($tradeQuantity, $tradeProceeds);

        return $this;
    }

    public function markClosed(): void {}

    public function isClosed(): bool
    {
        return false;
    }
}
