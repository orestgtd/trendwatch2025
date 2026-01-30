<?php

namespace App\Domain\Position\Model;

use App\Domain\Confirmation\ValueObjects\{
    CostAmount,
    ProceedsAmount,
    TradeQuantity,
};

use App\Domain\Position\{
    Model\Position,
    ValueObjects\BaseQuantity,
    ValueObjects\CostBase,
    ValueObjects\PositionQuantity,
};

use App\Domain\Kernel\{
    Values\PositionType,
};

use App\Domain\Security\{
    ValueObjects\SecurityInfo,
};

final class LongPosition extends Position
{
    private CostBase $costBase;

    private function __construct(
        SecurityInfo $securityInfo,
        PositionQuantity $positionQuantity,
        CostAmount $totalCost,
    ) {
        parent::__construct(
            $securityInfo,
            PositionType::long(),
            $positionQuantity,
        );

        $this->costBase = CostBase::create(
            BaseQuantity::fromPositionQuantity($positionQuantity),
            $totalCost,
        );
    }

    public static function create(
        SecurityInfo $securityInfo,
        PositionQuantity $positionQuantity,
        CostAmount $totalCost,
    ): self {
        return new self(
            $securityInfo,
            $positionQuantity,
            $totalCost,
        );
    }

    public static function fromPersisted(
        SecurityInfo $securityInfo,
        PositionQuantity $positionQuantity,
        CostAmount $totalCost,
        ProceedsAmount $totalProceeds,
    ): self {

        $instance = new self(
            $securityInfo,
            $positionQuantity,
            $totalCost,
        );

        $instance->costBase = CostBase::fromPersisted(
            BaseQuantity::fromPositionQuantity($positionQuantity),
            $totalCost,
            $totalProceeds
        );
    
        return $instance;
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

    public function expireQuantity(): void
    {
        $this->costBase->expire();
    }

    public function markClosed(): void {}

    public function isClosed(): bool
    {
        return false;
    }
}
