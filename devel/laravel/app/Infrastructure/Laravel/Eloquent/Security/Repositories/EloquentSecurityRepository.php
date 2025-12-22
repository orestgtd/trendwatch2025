<?php

namespace App\Infrastructure\Laravel\Eloquent\Security\Repositories;

use App\Domain\Outcome\{
    Persistence\PersistenceScope,
};

use App\Domain\Kernel\Identifiers\{
    SecurityNumber,
};

use App\Domain\Security\{
    Model\Security,
};

use App\Infrastructure\Laravel\Eloquent\Security\{
    Dto\PersistedSecurityDto,
    Model\Security as EloquentSecurity,
};

class EloquentSecurityRepository
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

    public function insert(Security $security): void
    {
        $this
            ->toEloquent($security)
            ->save();
    }

    public function update(Security $security, PersistenceScope $scope): void
    {
        $toUpdate = collect($scope->fields())
            ->mapWithKeys(fn(string $field) => [
                $field => match ($field) {
                    'variations' => $security->variations()
                },
            ])
            ->toArray();

        $security_number = (string) $security->securityNumber();

        EloquentSecurity::where('security_number', $security_number)->first()
            ->update($toUpdate);
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
