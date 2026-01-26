<?php

namespace App\Domain\Position\Behaviour;

use App\Domain\{
    Confirmation\Model\Confirmation,
    Kernel\Values\PositionType,
    Position\Model\LongPosition,
    Position\Model\Position,
    Position\Outcome\DecreasedHolding,
    RealizedGain\Model\RealizedGainBasis,
    RealizedGain\ValueObjects\RealizationSource,
};

use App\Foundation\Result;

final class SellToDecrease
{
    /** @return Result<DecreasedHolding> */
    public function do (Confirmation $confirmation, Position $position): Result
    {
        return $position->matchPositionType(
            onLong: fn () => $this->sellToCloseLongPosition($confirmation, $position),
            onShort: fn () => $this->sellToCloseShortPosition($confirmation)
        );
    }

    /** @return Result<DecreasedHolding> */
    private function sellToCloseLongPosition(Confirmation $confirmation, LongPosition $position): Result
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
            PositionType::long(),
            RealizationSource::trade($tradeNumber),
            $baseQuantity,
            $tradeQuantity,
            $position->getUnitType(),
            $position->getTotalCost(),
            $position->getTotalProceeds()
        );

        return Result::success(new DecreasedHolding(
            $position,
            $realizedGainBasis,
        ));
    }

    /** @return Result<DecreasedHolding> */
    private function sellToCloseShortPosition(Confirmation $confirmation): Result
    {
        $tradeNumber = $confirmation->getTradeNumber();
        return Result::failure(
            "Trade {$tradeNumber}: Cannot sell to close a short position."
        );
    }
}