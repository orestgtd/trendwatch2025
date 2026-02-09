<?php

namespace App\Application\ProcessTradeConfirmation\Services;

use App\Application\ProcessTradeConfirmation\{
    Actions\CreateNewSecurity,
    Actions\UpdateExistingSecurity,
    Dto\ParsedSecurityRequestDto,
    Lookups\SecurityLookup,
};

use App\Domain\{
    Security\Model\Security,
    Security\Outcome\SecurityOutcome,
};

use App\Foundation\Result;

class SecurityService
{
    public function __construct(
        private SecurityLookup $lookup,
        private CreateNewSecurity $createSecurity,
        private UpdateExistingSecurity $updateSecurity,
    ) {}

    /** @return Result<SecurityOutcome> */
    public function processSecurityRequest(ParsedSecurityRequestDto $requestDto): Result
    {
        return $this->lookup->matchBySecurityNumber(
            $requestDto->securityNumber,
            onNotFound: fn() => $this->createSecurity->createNewSecurityFromDto($requestDto),
            onExists: fn(Security $security) => $this->updateSecurity->updateSecurityFromDto($security, $requestDto)
        );
    }
}
