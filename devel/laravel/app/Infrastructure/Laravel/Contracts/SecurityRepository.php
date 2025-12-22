<?php

namespace App\Infrastructure\Laravel\Contracts;

use App\Domain\{
    Kernel\Identifiers\SecurityNumber,
    Security\Model\Security,
};

use App\Infrastructure\Laravel\Eloquent\Security\{
    Dto\PersistedSecurityDto,
};

interface SecurityRepository
{
    public function findBySecurityNumber(SecurityNumber $securityNumber): ?PersistedSecurityDto;
    public function save(Security $security): void;
}
