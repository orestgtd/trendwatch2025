<?php

namespace App\Application\Contracts;

use App\Domain\{
    Kernel\Identifiers\SecurityNumber,
    Security\Model\Security,
    Security\Record\SecurityRecord,
    Security\ValueObjects\Variations\VariationsInterface,
};

interface SecurityRepositoryContract
{
    // Commands
    public function insert(Security $security): void;
    public function updateVariations(SecurityNumber $securityNumber, VariationsInterface $variations): void;

    // Queries
    public function findBySecurityNumber(SecurityNumber $securityNumber): ?SecurityRecord;
}
