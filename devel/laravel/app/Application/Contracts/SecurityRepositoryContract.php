<?php

namespace App\Application\Contracts;

use App\Shared\Date;

use App\Domain\{
    Kernel\Identifiers\SecurityNumber,
    Security\Model\Security,
    Security\Record\SecurityRecord,
};
use App\Domain\Security\ValueObjects\Description;
use App\Domain\Security\ValueObjects\Variations\Variations;

interface SecurityRepositoryContract
{
    // Commands
    public function insert(Security $security): void;
    // public function updateDescription(SecurityNumber $securityNumber, Description $description): void;
    public function updateVariations(SecurityNumber $securityNumber, Variations $variations): void;


    // Queries
    public function findBySecurityNumber(SecurityNumber $securityNumber): ?SecurityRecord;
}
