<?php

namespace App\Application\Security\Queries;

use App\Domain\Security\{
    Builders\BuildSecurityFrom,
    Model\Security,
    ValueObjects\SecurityNumber,
};

use App\Infrastructure\Laravel\Eloquent\Security\{
    DTO\PersistedSecurityDTO,
    Repositories\EloquentSecurityRepository as SecurityRepository,
};

final class FindBySecurityNumberQuery
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

    private function buildSecurity(PersistedSecurityDTO $persisted): Security
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
