<?php

namespace App\Domain\Position\Behaviour;

use App\Domain\{
    Confirmation\Model\Confirmation,
    Position\Model\ShortPosition,
    Position\Outcome\NewPositionCreated,
    Position\ValueObjects\PositionQuantity,
};

use App\Foundation\Result;

final class CreateShortPosition
{
   /** @return Result<NewPositionCreated> */
    public function do(Confirmation $confirmation): Result
    {
        return Result::success(
            new NewPositionCreated(
                ShortPosition::create(
                    $confirmation->getSecurityInfo(),
                    PositionQuantity::fromTradeQuantity(
                        $confirmation->getTradeQuantity()
                    ),
                    $confirmation->netProceeds(),
                )
            )
        );
    }
}
