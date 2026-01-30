<?php

namespace App\Domain\Security\Model;

use App\Domain\Kernel\{
    Identifiers\SecurityNumber,
    Identifiers\Symbol,
    Values\ExpirationDate,
    Values\UnitType,
};

use App\Domain\Security\{
    Expiration\ExpirationRule,
    Outcome\NoChange,
    Outcome\SecurityOutcome,
    Outcome\VariationAdded,
    ValueObjects\Description,
    ValueObjects\SecurityInfo,
    ValueObjects\Variations\VariationsInterface,
};

abstract class Security
{
    protected SecurityInfo $info;
    protected VariationsInterface $variations;

    protected function __construct(
        SecurityInfo $securityInfo,
        VariationsInterface $variations,
    ) {

        $this->info = $securityInfo;
        $this->variations = $variations;
    }

    public function getSymbol(): Symbol { return $this->info->symbol; }
    public function getSecurityNumber(): SecurityNumber { return $this->info->securityNumber; }
    public function getCanonicalDescription(): Description { return $this->info->canonicalDescription; }
    public function getVariations(): VariationsInterface { return $this->variations; }
    public function getExpirationRule(): ExpirationRule { return $this->info->expirationRule; }
    public function getUnitType(): UnitType { return $this->info->unitType; }

    public function recordDescription(Description $incomingDescription): SecurityOutcome
    {
        // Already canonical
        if ($incomingDescription->equals($this->getCanonicalDescription())) {
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
}
