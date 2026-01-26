<?php

namespace App\Domain\Position\Behaviour;

use App\Domain\{
    Confirmation\Model\Confirmation,
    Kernel\Values\PositionType,
    Position\Model\ShortPosition,
    Position\Model\Position,
    Position\Outcome\DecreasedHolding,
    RealizedGain\Model\RealizedGainBasis,
    RealizedGain\ValueObjects\RealizationSource,
};

use App\Foundation\Result;

final class BuyToDecrease
{
    /** @return Result<DecreasedHolding> */
    public function do (Confirmation $confirmation, Position $position): Result
    {
        return $position->matchPositionType(
            onShort: fn () => $this->buyToDecreaseShortPosition($confirmation, $position),
            onLong: fn () => $this->buyToDecreaseLongPosition($confirmation)
        );
    }

    /** @return Result<DecreasedHolding> */
    private function buyToDecreaseShortPosition(Confirmation $confirmation, ShortPosition $short): Result
    {
        $securityNumber = $confirmation->getSecurityNumber();
        $tradeNumber = $confirmation->getTradeNumber();
        $tradeQuantity = $confirmation->getTradeQuantity();
        $baseQuantity = $short->getBaseQuantity();

        $short->addCover(
            $tradeQuantity,
            $confirmation->netCost()
        );

        $realizedGainBasis = RealizedGainBasis::create(
            $securityNumber,
            PositionType::short(),
            RealizationSource::trade($tradeNumber),
            $baseQuantity,
            $tradeQuantity,
            $short->getUnitType(),
            $short->getTotalCost(),
            $short->getTotalProceeds()
        );

        return Result::success(
            new DecreasedHolding(
                $short,
                $realizedGainBasis,
            )
        );
    }

    /** @return Result<DecreasedHolding> */
    private function buyToDecreaseLongPosition(Confirmation $confirmation): Result
    {
        $tradeNumber = $confirmation->getTradeNumber();
        return Result::failure(
            "Trade {$tradeNumber}: Cannot buy to decrease a long position."
        );
    }
}