<?php

namespace App\Infrastructure\Laravel\Eloquent\Security\Repositories;

use App\Application\{
    Contracts\SecurityRepositoryContract,
};

use App\Domain\{
    Kernel\Identifiers\SecurityNumber,
    Security\Model\Security,
    Security\Record\SecurityRecord,
    Security\ValueObjects\Description,
    Security\ValueObjects\Variations\Variations,
};

use App\Infrastructure\Laravel\Eloquent\Security\{
    Model\Security as EloquentSecurity,
};

class EloquentSecurityRepository implements SecurityRepositoryContract
{
    public function findBySecurityNumber(SecurityNumber $securityNumber): ?SecurityRecord
    {
        $eloquent = EloquentSecurity::where('security_number', (string) $securityNumber)->first();

        return $eloquent
            ? new SecurityRecord(
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

    public function updateVariations(SecurityNumber $securityNumber, Variations $variations): void
    {
        EloquentSecurity::where('security_number', (string) $securityNumber)->first()
            ->update(['variations' => $variations]);
    }

    public function updateDescription(SecurityNumber $securityNumber, Description $description): void
    {
        EloquentSecurity::where('security_number', (string) $securityNumber)->first()
            ->update(['canonical_description' => (string) $description]);
    }

    private function toEloquent(Security $security): EloquentSecurity
    {
        $eloquent = new EloquentSecurity();

        $eloquent->security_number = $security->getSecurityNumber();
        $eloquent->symbol = $security->getSymbol();
        $eloquent->canonical_description = $security->getCanonicalDescription();
        $eloquent->variations = $security->getVariations();
        $eloquent->unit_type = $security->getUnitType();
        $eloquent->expiration_date = $security->getExpirationRule()->getExpirationDate();

        return $eloquent;
    }
}
