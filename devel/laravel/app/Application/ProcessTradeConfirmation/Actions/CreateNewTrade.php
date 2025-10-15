<?php

namespace App\Application\ProcessTradeConfirmation\Actions;

use App\Application\ProcessTradeConfirmation\{
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
                    $dto->tradeUnitType,
                    $dto->unitPrice,
                    $dto->commission,
                    $dto->usTax,
                )
            )
        );
    }
}
