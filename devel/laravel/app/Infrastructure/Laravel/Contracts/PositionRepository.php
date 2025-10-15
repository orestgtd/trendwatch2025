<?php

namespace App\Infrastructure\Laravel\Contracts;

use App\Domain\{
    Position\Model\Position,
    Security\ValueObjects\SecurityNumber,
};

use App\Infrastructure\Laravel\Eloquent\Position\{
    Dto\PersistedPositionDto,
};

interface PositionRepository
{
    public function findBySecurityNumber(SecurityNumber $securityNumber): ?PersistedPositionDto;
    public function save(Position $security): void;
}
