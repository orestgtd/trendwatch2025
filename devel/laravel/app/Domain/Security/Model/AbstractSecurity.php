<?php

namespace App\Domain\Security\Model;

use App\Domain\Kernel\{
    Identifiers\SecurityNumber,
    Values\UnitType,
};

use App\Domain\Security\{
    Model\Security,
    Outcome\NoChange,
    Outcome\SecurityOutcome,
    Outcome\VariationAdded,
    ValueObjects\Description,
    ValueObjects\Symbol,
    ValueObjects\Variations\VariationsInterface,
};

abstract class AbstractSecurity implements Security
{
    protected Description $canonicalDescription;
    protected SecurityNumber $securityNumber;
    protected Symbol $symbol;
    protected UnitType $unitType;
    protected VariationsInterface $variations;

    protected function __construct(
        SecurityNumber $securityNumber,
        Symbol $symbol,
        UnitType $unitType,
        Description $canonicalDescription,
        VariationsInterface $variations
    ) {
        $this->securityNumber = $securityNumber;
        $this->symbol = $symbol;
        $this->unitType = $unitType;
        $this->canonicalDescription = $canonicalDescription;
        $this->variations = $variations;
    }

    public function symbol(): Symbol { return $this->symbol; }
    public function securityNumber(): SecurityNumber { return $this->securityNumber; }
    public function canonicalDescription(): Description { return $this->canonicalDescription; }
    public function variations(): VariationsInterface { return $this->variations; }

    public function recordDescription(Description $incomingDescription): SecurityOutcome
    {
        // Already canonical
        if ($incomingDescription->equals($this->canonicalDescription)) {
            return new NoChange($this);
        }

        // Already exists in variations
        if ($this->variations->contains($incomingDescription)) {
            return new NoChange($this);
        }

        // Add to variations
        $this->variations = $this->variations->add($incomingDescription);

        return new VariationAdded($this);
    }

    public function unitType(): UnitType
    {
        return $this->unitType;
    }
}
