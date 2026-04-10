<?php

namespace App\Application\Contracts;

use App\Foundation\Date;

use App\Domain\{
    Confirmation\ValueObjects\CostAmount,
    Kernel\Identifiers\SecurityNumber,
    Position\Model\Position,
    Position\Record\PositionRecord,
    Position\ValueObjects\PositionQuantity,
};

interface PositionRepositoryContract
{
    // Commands
    public function delete(Position $position): void;
    public function insert(Position $position): void;
    public function update(SecurityNumber $securityNumber, PositionQuantity $quantity, CostAmount $totalCost): void;


    // Queries
    public function active(): array;
    public function expiredAsOf(Date $asOf): array;
    public function findBySecurityNumber(SecurityNumber $securityNumber): ?PositionRecord;
}
