<?php

namespace App\Domain\Position\Behaviour;

use App\Domain\{
    Confirmation\Model\Confirmation,
    Position\ValueObjects\PositionQuantity,
    Position\Model\LongPosition,
    Position\Outcome\NewPositionCreated,
};

use App\Shared\Result;

final class CreateLongPosition
{
    /** @return Result<NewPositionCreated> */
    public function do(Confirmation $confirmation): Result
    {
        return Result::success(
            new NewPositionCreated(
                LongPosition::create(
                    $confirmation->getSecurityInfo(),
                    PositionQuantity::fromTradeQuantity(
                        $confirmation->getTradeQuantity()
                    ),
                    $confirmation->netCost(),
                )
            )
        );
    }
}
