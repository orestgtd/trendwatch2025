<?php

namespace App\Application\ProcessTradeConfirmation\Services;

use App\Application\ProcessTradeConfirmation\{
    Dto\ParsedSecurityRequestDto,
    Queries\FindSecurityQuery,
    Actions\CreateNewSecurity,
    Actions\UpdateExistingSecurity,
};

use App\Domain\Security\Outcome\SecurityOutcome;

use App\Shared\Result;

class SecurityService
{
    public function __construct(
        private FindSecurityQuery $findSecurity,
        private CreateNewSecurity $createSecurity,
        private UpdateExistingSecurity $updateSecurity,
    ) {}

    /** @return Result<SecurityOutcome> */
    public function processSecurityRequest(ParsedSecurityRequestDto $requestDto): Result
    {
        $security = $this->findSecurity->findBySecurityNumber($requestDto->securityNumber);

        return $security
        ? $this->updateSecurity->updateSecurityFromDto($security, $requestDto)
        : $this->createSecurity->createNewSecurityFromDto($requestDto);
    }
}
