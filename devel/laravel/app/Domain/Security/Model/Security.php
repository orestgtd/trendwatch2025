<?php

namespace App\Domain\Security\Model;

use App\Domain\Kernel\{
    Identifiers\SecurityNumber,
    Identifiers\Symbol,
    Values\UnitType,
};

use App\Domain\Security\Outcome\SecurityOutcome;

use App\Domain\Security\ValueObjects\{
    Description,
    ExpirationDate\ExpirationDate,
    Variations\VariationsInterface,
};

interface Security
{
    public function securityNumber(): SecurityNumber;
    public function symbol(): Symbol;
    public function canonicalDescription(): Description;
    public function variations(): VariationsInterface;
    public function expirationDate(): ExpirationDate;
    public function unitType(): UnitType;
    public function recordDescription(Description $incomingDescription): SecurityOutcome;
}
