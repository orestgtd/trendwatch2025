<?php

namespace App\Domain\Position\Behaviour;

use App\Domain\Confirmation\{
    Model\Confirmation,
};

use App\Domain\Position\{
    Model\LongPosition,
    Model\ShortPosition,
    Model\Position,
    Outcome\IncreasedHolding,
    Outcome\NewPositionCreated,
    Outcome\PositionOutcome,
    ValueObjects\PositionQuantity,
};

final class AddToNewOrExistingPosition
{
    public function do(Confirmation $confirmation, ?Position $lookupPosition): PositionOutcome
    {
        return
            $confirmation->matchTradeAction(
                onBuy: fn(Confirmation $confirmation) => $this->addToNewOrExistingLongPosition($confirmation, $lookupPosition),
                onSell: fn(Confirmation $confirmation) => $this->addToNewOrExistingShortPosition($confirmation, $lookupPosition),
            );
    }

    private function addToNewOrExistingLongPosition(Confirmation $confirmation, ?Position $lookupPosition): PositionOutcome
    {
        return $lookupPosition
            ? $this->addToExistingLongPosition($confirmation, $lookupPosition)
            : $this->createNewLongPosition($confirmation);
    }

    private function createNewLongPosition(Confirmation $confirmation): NewPositionCreated
    {
        return new NewPositionCreated(
            LongPosition::create(
                $confirmation->getSecurityNumber(),
                PositionQuantity::fromTradeQuantity($confirmation->getTradeQuantity()),
                $confirmation->getUnitType(),
                $confirmation->netCost(),
            ),
            $confirmation->getTradeNumber()
        );
    }

    private function createNewShortPosition(Confirmation $confirmation): NewPositionCreated
    {
        return new NewPositionCreated(
            ShortPosition::create(
                $confirmation->getSecurityNumber(),
                PositionQuantity::fromTradeQuantity($confirmation->getTradeQuantity()),
                $confirmation->getUnitType(),
                $confirmation->netProceeds(),
            ),
            $confirmation->getTradeNumber()
        );
    }

    private function addToExistingLongPosition(Confirmation $confirmation, LongPosition $position): IncreasedHolding
    {
        return new IncreasedHolding(
            $position->addPurchase(
                $confirmation->getTradeQuantity(),
                $confirmation->netCost()
            ),
            $confirmation->getTradeNumber()
        );
    }

    private function addToExistingShortPosition(Confirmation $confirmation, ShortPosition $position): IncreasedHolding
    {
        return new IncreasedHolding(
            $position->increaseHolding(
                $confirmation->getTradeQuantity(),
                $confirmation->netProceeds()
            ),
            $confirmation->getTradeNumber()
        );
    }

    private function addToNewOrExistingShortPosition(Confirmation $confirmation, ?Position $lookupPosition): PositionOutcome
    {
        return $lookupPosition
            ? $this->addToExistingShortPosition($confirmation, $lookupPosition)
            : $this->createNewShortPosition($confirmation);
    }
}
