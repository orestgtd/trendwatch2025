<?php

namespace App\Application\Trade\Services\Strategies;

use App\Application\Trade\{
    Dto\ParsedTradeRequestDto,
};
use App\Domain\Confirmation\{
    Builders\BuildNewConfirmation,
    Outcome\NewConfirmationCreated,
};

use App\Shared\Result;

final class CreateNewTrade
{
    /** @return Result<ConfirmationOutome> */
    public function createNewTradeFromDto(ParsedTradeRequestDto $dto): Result
    {
        return Result::success(
            new NewConfirmationCreated(
                BuildNewConfirmation::from(
                    $dto->securityNumber,
                    $dto->tradeNumber,
                    $dto->tradeAction,
                    $dto->positionEffect,
                    $dto->tradeQuantity
                )
            )
        );
    }
}
