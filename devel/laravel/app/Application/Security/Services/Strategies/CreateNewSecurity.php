<?php

namespace App\Application\Security\Services\Strategies;

use App\Application\Security\{
    Dto\ParsedSecurityRequestDto,
};

use App\Domain\Security\{
    Builders\BuildNewSecurity,
    Model\Security,
    Outcome\NewSecurityCreated,
    ValueObjects\Variations\NoVariations,
};

use App\Shared\Result;

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
