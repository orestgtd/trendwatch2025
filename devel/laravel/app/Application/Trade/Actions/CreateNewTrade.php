<?php

namespace App\Application\Trade\Actions;

use App\Application\Trade\{
    Dto\ParsedTradeRequestDto,
};
use App\Domain\Confirmation\{
    Builders\BuildNewConfirmation,
    Outcome\ConfirmationOutcome,
    Outcome\NewConfirmationCreated,
};

use App\Shared\Result;

final class CreateNewTrade
{
    /** @return Result<ConfirmationOutcome> */
    public function createNewTradeFromDto(ParsedTradeRequestDto $dto): Result
    {
        return Result::success(
            new NewConfirmationCreated(
                BuildNewConfirmation::from(
                    $dto->securityNumber,
                    $dto->tradeNumber,
                    $dto->tradeAction,
                    $dto->positionEffect,
                    $dto->tradeQuantity,
                    $dto->unitPrice,
                    $dto->commission,
                    $dto->usTax,
                )
            )
        );
    }
}
