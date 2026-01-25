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
    ValueObjects\PositionQuantity,
};

use App\Domain\Kernel\{
    Identifiers\SecurityNumber,
    Identifiers\Symbol,
    Values\ExpirationDate,
    Values\PositionType,
    Values\UnitType,
};

final class LongPosition extends AbstractPosition
{
    private CostBase $costBase;

    private function __construct(
        SecurityNumber $securityNumber,
        Symbol $symbol,
        PositionQuantity $positionQuantity,
        UnitType $unitType,
        CostAmount $totalCost,
        ExpirationDate $expirationDate,
    ) {
        $this->securityNumber = $securityNumber;
        $this->symbol = $symbol;
        $this->costBase = CostBase::create(
            BaseQuantity::fromPositionQuantity($positionQuantity),
            $totalCost,
        );
        $this->unitType = $unitType;
        $this->expirationDate = $expirationDate;
    }

    public static function create(
        SecurityNumber $securityNumber,
        Symbol $symbol,
        PositionQuantity $positionQuantity,
        UnitType $unitType,
        CostAmount $totalCost,
        ExpirationDate $expirationDate,
    ): self {
        return new self(
            $securityNumber,
            $symbol,
            $positionQuantity,
            $unitType,
            $totalCost,
            $expirationDate
        );
    }

    public static function fromPersisted(
        SecurityNumber $securityNumber,
        Symbol $symbol,
        PositionQuantity $positionQuantity,
        UnitType $unitType,
        CostAmount $totalCost,
        ProceedsAmount $totalProceeds,
        ExpirationDate $expirationDate,
    ): self {
        $instance = new self($securityNumber, $symbol, $positionQuantity, $unitType, $totalCost, $expirationDate);
        $instance->costBase = CostBase::fromPersisted(
            BaseQuantity::fromPositionQuantity($positionQuantity),
            $totalCost,
            $totalProceeds
        );
        return $instance;
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
    public function getUnitType(): UnitType
    {
        return $this->unitType;        
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
