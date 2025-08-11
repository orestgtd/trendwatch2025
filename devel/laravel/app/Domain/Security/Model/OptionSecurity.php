<?php

namespace App\Domain\Security\Model;

use App\Domain\Security\ValueObjects\{
    Description,
    ExpirationDate\ExpirationDate,
    SecurityNumber,
    Symbol,
    UnitType,
    Variations\VariationsInterface,
};

final class OptionSecurity extends AbstractSecurity
{
    private ExpirationDate $expirationDate;

    private function __construct(
        SecurityNumber $securityNumber,
        Symbol $symbol,
        Description $canonicalDescription,
        VariationsInterface $variations,
        ExpirationDate $expirationDate
    ) {
        $this->securityNumber = $securityNumber;
        $this->symbol = $symbol;
        $this->canonicalDescription = $canonicalDescription;
        $this->variations = $variations;
        $this->expirationDate = $expirationDate;
    }

    public static function create(
        SecurityNumber $securityNumber,
        Symbol $symbol,
        Description $canonicalDescription,
        VariationsInterface $variations,
        ExpirationDate $expirationDate
    ): self {

        return new self(
            $securityNumber,
            $symbol,
            $canonicalDescription,
            $variations,
            $expirationDate
        );
    }

    public function securityNumber(): SecurityNumber { return $this->securityNumber; }
    public function symbol(): Symbol { return $this->symbol; }
    public function canonicalDescription(): Description { return $this->canonicalDescription; }
    public function variations(): VariationsInterface { return $this->variations; }
    public function expirationDate(): ExpirationDate { return $this->expirationDate; }

    public function unitType(): UnitType
    {
        return UnitType::contracts();
    }

}
