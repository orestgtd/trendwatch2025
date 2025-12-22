<?php

namespace App\Domain\Security\Repositories;

use App\Domain\Kernel\Identifiers\{
    SecurityNumber,
};

use App\Domain\Security\{
    Dto\SecurityData,
    Model\Security,
};

interface SecurityRepository
{
    public function findBySecurityNumber(SecurityNumber $securityNumber): ?SecurityData;
    public function save(Security $security): void;
}
