<?php

namespace App\Application\ProcessTradeConfirmation\Queries;

use App\Domain\Security\{
    Builders\BuildSecurityFrom,
    Model\Security,
    ValueObjects\SecurityNumber,
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
            $persisted->securityNumber,
            $persisted->symbol,
            $persisted->canonicalDescription,
            $persisted->variations,
            $persisted->unitType,
            $persisted->expirationDate
        );
    }
}
