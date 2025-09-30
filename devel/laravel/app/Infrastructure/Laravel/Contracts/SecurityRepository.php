<?php

namespace App\Infrastructure\Laravel\Contracts;

use App\Domain\Security\{
    Model\Security,
    ValueObjects\SecurityNumber,
};

use App\Infrastructure\Laravel\Eloquent\Security\{
    Dto\PersistedSecurityDto,
};

interface SecurityRepository
{
    public function findBySecurityNumber(SecurityNumber $securityNumber): ?PersistedSecurityDto;
    public function save(Security $security): void;
}
