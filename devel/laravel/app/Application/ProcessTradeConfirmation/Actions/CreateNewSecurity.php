<?php

namespace App\Application\ProcessTradeConfirmation\Actions;

use App\Application\ProcessTradeConfirmation\{
    Dto\ParsedSecurityRequestDto,
};

use App\Domain\Security\{
    Builders\BuildNewSecurity,
    Model\Security,
    Outcome\NewSecurityCreated,
    ValueObjects\Variations\NoVariations,
};

use App\Foundation\Result;

final class CreateNewSecurity
{
    /** @return Result<\App\Domain\Security\Outcome\SecurityOutcome> */
    public function createNewSecurityFromDto(ParsedSecurityRequestDto $dto): Result
    {
        return BuildNewSecurity::tryFrom(
            $dto->securityNumber,
            $dto->symbol,
            $dto->description,
            NoVariations::create(),
            $dto->unitType,
            $dto->expirationDate)
        ->map(
            fn (Security $security) => new NewSecurityCreated($security)
        );
    }
}
