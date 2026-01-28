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

use App\Domain\Security\{
    ValueObjects\SecurityInfo,
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
                    $dto->securityInfo,
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
