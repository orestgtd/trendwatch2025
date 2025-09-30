<?php

namespace App\Infrastructure\Laravel\Eloquent\Security\Repositories;

use App\Domain\Security\{
    Model\Security,
    ValueObjects\SecurityNumber,
};

use App\Infrastructure\Laravel\Eloquent\Security\{
    Dto\PersistedSecurityDto,
    Model\Security as EloquentSecurity,
};

use App\Infrastructure\Laravel\Contracts\SecurityRepository;

final class EloquentSecurityRepository implements SecurityRepository
{
    public function findBySecurityNumber(SecurityNumber $securityNumber): ?PersistedSecurityDto
    {
        $eloquent = EloquentSecurity::where('security_number', (string) $securityNumber)->first();

        return $eloquent
            ? new PersistedSecurityDTO(
                $eloquent->security_number,
                $eloquent->symbol,
                $eloquent->canonical_description,
                $eloquent->variations,
                $eloquent->unit_type,
                $eloquent->expiration_date,
            )
            : $eloquent;
    }

    public function save(Security $security): void
    {
        $this
            ->toEloquent($security)
            ->save();
    }

    private function toEloquent(Security $security): EloquentSecurity
    {
        $eloquent = new EloquentSecurity();

        $eloquent->security_number = $security->securityNumber();
        $eloquent->symbol = $security->symbol();
        $eloquent->canonical_description = $security->canonicalDescription();
        $eloquent->variations = $security->variations();
        $eloquent->unit_type = $security->unitType();
        $eloquent->expiration_date = $security->expirationDate();

        return $eloquent;
    }
}
