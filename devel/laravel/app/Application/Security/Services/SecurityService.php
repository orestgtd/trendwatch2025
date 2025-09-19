<?php

namespace App\Application\Security\Services;

use App\Application\Security\{
    Dto\ParsedSecurityRequestDto,
    Queries\FindBySecurityNumberQuery,
    Services\Strategies\CreateNewSecurity,
    Services\Strategies\UpdateExistingSecurity,
};

use App\Domain\Security\{
    Model\Security,
};

use App\Shared\Result;

class SecurityService
{
    public function __construct(
        private FindBySecurityNumberQuery $findSecurity,
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
