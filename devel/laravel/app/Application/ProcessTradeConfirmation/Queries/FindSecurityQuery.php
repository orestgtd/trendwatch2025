<?php

namespace App\Application\ProcessTradeConfirmation\Queries;

use App\Domain\Kernel\Identifiers\{
    SecurityNumber,
};
use App\Domain\Kernel\Values\ExpirationDate;
use App\Domain\Security\{
    Builders\BuildSecurityFrom,
    Expiration\ExpirationRule,
    Model\Security,
    ValueObjects\SecurityInfo,
};

use App\Infrastructure\Laravel\Eloquent\Security\{
    Dto\PersistedSecurityDto,
    Repositories\EloquentSecurityRepository as SecurityRepository,
};

final class FindSecurityQuery
{
    public function __construct(
        private readonly SecurityRepository $repository
    ){}

    public function findBySecurityNumber(SecurityNumber $securityNumber): ?Security
    {
        $persisted = $this->repository->findBySecurityNumber($securityNumber);

        return $persisted
        ? $this->buildSecurity($persisted)
        : $persisted;
    }

    private function buildSecurity(PersistedSecurityDto $persisted): Security
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
