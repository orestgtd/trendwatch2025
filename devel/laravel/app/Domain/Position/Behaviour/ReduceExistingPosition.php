<?php

namespace App\Domain\Position\Behaviour;

use App\Domain\Confirmation\{
    Model\Confirmation,
};

use App\Domain\Position\{
    Model\LongPosition,
    Model\ShortPosition,
    Model\Position,
    Outcome\Holdings\DecreasedHolding,
    Outcome\PositionOutcome,
};

use App\Domain\RealizedGain\{
    Model\RealizedGainBasis,
    ValueObjects\RealizationSource,
};

final class ReduceExistingPosition
{
    public function do(Confirmation $confirmation, Position $position): PositionOutcome
    {
        return
            $confirmation->matchTradeAction(
                onBuy: fn(Confirmation $confirmation) => $this->reduceExistingShortPosition($confirmation, $position),
                onSell: fn(Confirmation $confirmation) => $this->reduceExistingLongPosition($confirmation, $position),
            );
    }

    private function reduceExistingLongPosition(Confirmation $confirmation, LongPosition $position): PositionOutcome
    {
        $securityNumber = $confirmation->getSecurityNumber();
        $tradeNumber = $confirmation->getTradeNumber();
        $tradeQuantity = $confirmation->getTradeQuantity();
        $baseQuantity = $position->getBaseQuantity();

        $position->addSale(
            $tradeQuantity,
            $confirmation->netProceeds()
        );

        $realizedGainBasis = RealizedGainBasis::create(
            $securityNumber,
            RealizationSource::trade($tradeNumber),
            $baseQuantity,
            $tradeQuantity,
            $position->getUnitType(),
            $position->getTotalCost(),
            $position->getTotalProceeds()
        );

        return new DecreasedHolding(
            $position,
            $realizedGainBasis,
        );
    }

    private function reduceExistingShortPosition(Confirmation $confirmation, ShortPosition $position): PositionOutcome
    {
        $securityNumber = $confirmation->getSecurityNumber();
        $tradeNumber = $confirmation->getTradeNumber();
        $tradeQuantity = $confirmation->getTradeQuantity();
        $baseQuantity = $position->getBaseQuantity();

        $position->addCover(
            $tradeQuantity,
            $confirmation->netCost()
        );

        $realizedGainBasis = RealizedGainBasis::create(
            $securityNumber,
            RealizationSource::trade($tradeNumber),
            $baseQuantity,
            $tradeQuantity,
            $position->getUnitType(),
            $position->getTotalCost(),
            $position->getTotalProceeds()
        );

        return new DecreasedHolding(
            $position,
            $realizedGainBasis,
        );
    }
}
