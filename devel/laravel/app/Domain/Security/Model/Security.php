<?php

namespace App\Domain\Security\Model;

use App\Domain\Security\ValueObjects\{
    Description,
    ExpirationDate\ExpirationDateInterface,
    SecurityNumber,
    Symbol,
    UnitType,
    Variations\VariationsInterface,
};

interface Security
{
    public function securityNumber(): SecurityNumber;
    public function symbol(): Symbol;
    public function canonicalDescription(): Description;
    public function variations(): VariationsInterface;
    public function expirationDate(): ExpirationDateInterface;
    public function unitType(): UnitType;
}
