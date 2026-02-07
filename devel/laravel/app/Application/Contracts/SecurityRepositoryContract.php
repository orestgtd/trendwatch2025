<?php

namespace App\Application\Contracts;

use App\Shared\Date;

use App\Domain\{
    Kernel\Identifiers\SecurityNumber,
    Outcome\Persistence\PersistenceScope,
    Security\Model\Security,
    Security\Record\SecurityRecord,
};

interface SecurityRepositoryContract
{
    // Commands
    public function insert(Security $position): void;
    public function update(Security $position, PersistenceScope $scope): void;

    // Queries
    public function findBySecurityNumber(SecurityNumber $securityNumber): ?SecurityRecord;
}
