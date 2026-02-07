<?php

namespace App\Application\ProcessTradeConfirmation\Lookups;

use App\Domain\{
    Kernel\Identifiers\SecurityNumber,
    Security\Builders\BuildSecurityFrom,
    Security\Expiration\ExpirationRule,
    Security\Model\Security,
    Security\Outcome\SecurityOutcome,
    Security\ValueObjects\SecurityInfo,
};

use App\Infrastructure\Laravel\Eloquent\Security\{
    Dto\PersistedSecurityDto,
    Repositories\EloquentSecurityRepository as SecurityRepository,
};

use App\Shared\Result;

final class SecurityLookup
{
    public function __construct(
        private readonly SecurityRepository $repository
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
            : $onExists($this->buildSecurityFromPersisted($persisted));
    }

    private function buildSecurityFromPersisted(PersistedSecurityDto $persisted): Security
    {
        return BuildSecurityFrom::from(
            SecurityInfo::from(
                $persisted->securityNumber,
                $persisted->symbol,
                $persisted->canonicalDescription,
                $persisted->unitType,
                ExpirationRule::fromNullableDate($persisted->expirationDate)
            ),
            $persisted->variations,
        );
    }
}
