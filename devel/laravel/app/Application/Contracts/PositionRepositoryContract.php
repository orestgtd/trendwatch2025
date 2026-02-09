<?php

namespace App\Application\Contracts;

use App\Foundation\Date;

use App\Domain\{
    Kernel\Identifiers\SecurityNumber,
    Outcome\Persistence\PersistenceScope,
    Position\Model\Position,
    Position\Record\PositionRecord,
};

interface PositionRepositoryContract
{
    // Commands
    public function delete(Position $position): void;
    public function insert(Position $position): void;
    public function save(Position $position): void;
    public function update(Position $position, PersistenceScope $scope): void;
    public function upsert(Position $position): void;

    // Queries
    public function active(): array;
    public function expiredAsOf(Date $asOf): array;
    public function findBySecurityNumber(SecurityNumber $securityNumber): ?PositionRecord;
}
