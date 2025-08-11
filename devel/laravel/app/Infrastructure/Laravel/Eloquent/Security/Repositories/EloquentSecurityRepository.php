<?php

namespace App\Infrastructure\Laravel\Eloquent\Security\Repositories;

use App\Domain\Security\{
    Model\Security,
    Dto\SecurityData,
    Repositories\SecurityRepository,
    ValueObjects\SecurityNumber,
};

use App\Infrastructure\Laravel\Eloquent\Security\{
    Mapper\SecurityMapper,
    Model\Security as EloquentSecurity,
};

final class EloquentSecurityRepository implements SecurityRepository
{
    public function findBySecurityNumber(SecurityNumber $securityNumber): ?SecurityData
    {
        /** @var \App\Infrastructure\Laravel\Eloquent\Security\Model\Security|null $record */
        $record = EloquentSecurity::where('security_number', (string) $securityNumber)->first();

        return $record
            ? SecurityMapper::toDto($record)
            : null;
    }

    public function save(Security $security): void
    {
        $eloquent = SecurityMapper::toEloquent($security);
        $eloquent->save();
    }

}
