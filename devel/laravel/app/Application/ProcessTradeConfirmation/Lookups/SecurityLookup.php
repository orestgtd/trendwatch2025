<?php

namespace App\Application\ProcessTradeConfirmation\Lookups;

use App\Application\Contracts\{
    SecurityRepositoryContract,
};

use App\Domain\{
    Kernel\Identifiers\SecurityNumber,
    Security\Builders\BuildSecurityFromRecord,
    Security\Expiration\ExpirationRule,
    Security\Model\Security,
    Security\Outcome\SecurityOutcome,
    Security\ValueObjects\SecurityInfo,
    Security\Record\SecurityRecord,
};

use App\Shared\Result;

final class SecurityLookup
{
    public function __construct(
        private readonly SecurityRepositoryContract $repository
    ){}

        /**
     * @param callable(Security): Result<SecurityOutcome> $onExists
     * @param callable(): Result<SecurityOutcome> $onNotFound
     *
     * @return Result<SecurityOutcome>
     */
    public function matchBySecurityNumber(
        SecurityNumber $securityNumber,
        callable $onExists,
        callable $onNotFound,
    ): Result
    {
        $persisted = $this->repository->findBySecurityNumber($securityNumber);

        return is_null($persisted)
            ? $onNotFound()
            : $onExists($this->buildSecurityFromRecord($persisted));
    }

    private function buildSecurityFromRecord(SecurityRecord $record): Security
    {
        return BuildSecurityFromRecord::from($record);
    }
}
