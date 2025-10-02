<?php

namespace App\Application\ProcessTradeConfirmation\Actions;

use App\Application\ProcessTradeConfirmation\{
    Dto\ParsedSecurityRequestDto,
};

use App\Domain\Security\{
    Model\Security,
};

use App\Shared\Result;

final class UpdateExistingSecurity
{
    /** @return Result<\App\Domain\Security\Outcome\SecurityOutcome> */
    public function updateSecurityFromDto(Security $security, ParsedSecurityRequestDto $requestDto): Result
    {
        return Result::success(
            $security->recordDescription($requestDto->description)
        );
    }
}
