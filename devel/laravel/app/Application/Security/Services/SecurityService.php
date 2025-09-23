<?php

namespace App\Application\Security\Services;

use App\Application\Security\{
    Dto\ParsedSecurityDto,
};

use App\Domain\Security\{
    Context\SecurityContextWithOutcome,
    Outcome\NewSecurityCreated,
    Repositories\SecurityRepository,
    ValueObjects\Variations\NoVariations,
    SecurityFactory,
    Model\Security,
};

use App\Shared\Result;

class SecurityService
{
    public function __construct(
        private SecurityRepository $repo,

    ) {}

    /** @return Result<SecurityContextWithOutcome> */
    public function processSecurityRequestDto(ParsedSecurityDto $dto): Result
    {
        $maybeSecurity = $this->lookupUsingDto($dto);

        return $this->processNewOrExistingSecurity($maybeSecurity, $dto);
    }

    private function lookupUsingDto(ParsedSecurityDto $dto): ?Security
    {
        return $this->repo->findBySecurityNumber($dto->securityNumber);
        
    }

    /**return Result<SecurityContextWithOutcome> */
    private function processNewOrExistingSecurity(?Security $security, ParsedSecurityDto $dto): Result
    {
        return $security
            ? $this->processExistingSecurityWithDto($security, $dto)
            : $this->createNewSecurityFromDto($dto);
    }

    /** @return Result<SecurityContextWithOutcome> */
    private function createNewSecurityFromDto(ParsedSecurityDto $dto): Result
    {
        return
            SecurityFactory::tryFrom(
                $dto->securityNumber,
                $dto->symbol,
                $dto->description,
                NoVariations::create(),
                $dto->unitType,
                $dto->expirationDate)
            ->map(
                fn(Security $security): SecurityContextWithOutcome => new SecurityContextWithOutcome(
                    $security,
                    new NewSecurityCreated
                )
        );
    }

    /** @return Result<SecurityInterface> */
    private function processExistingSecurityWithDto(Security $security, ParsedSecurityDto $dto): Result
    {
        dd($dto);
    }
}
