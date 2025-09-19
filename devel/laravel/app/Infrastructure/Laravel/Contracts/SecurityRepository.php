<?php

namespace App\Infrastructure\Laravel\Contracts;

use App\Domain\Security\{
    Model\Security,
    ValueObjects\SecurityNumber,
};

use App\Infrastructure\Laravel\Eloquent\Security\{
    DTO\PersistedSecurityDTO,
};

interface SecurityRepository
{
    public function findBySecurityNumber(SecurityNumber $securityNumber): ?PersistedSecurityDTO;
    public function save(Security $security): void;
}
