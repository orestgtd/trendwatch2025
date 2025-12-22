<?php

namespace App\Domain\Security\Model;

use App\Domain\Kernel\Identifiers\{
    SecurityNumber,
};

use App\Domain\Security\Outcome\{
    NoChange,
    SecurityOutcome,
    VariationAdded,
};

use App\Domain\Security\ValueObjects\{
    Description,
    Symbol,
    Variations\VariationsInterface,
};

abstract class AbstractSecurity implements Security
{
    protected Description $canonicalDescription;
    protected SecurityNumber $securityNumber;
    protected Symbol $symbol;
    protected VariationsInterface $variations;

    public function __construct(
        SecurityNumber $securityNumber,
        Symbol $symbol,
        Description $canonicalDescription,
        VariationsInterface $variations
    ) {
        $this->securityNumber = $securityNumber;
        $this->symbol = $symbol;
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

    /**
     * Apply a draft and return a domain-specific result.
     */
    // public function applyDraft(SecurityDraftForDomain $draft): SecurityOutcome
    // {
    //     $incomingDescription = $draft->description;

    //     // Already canonical
    //     if ($incomingDescription->equals($this->canonicalDescription)) {
    //         return new NoChange($this);
    //     }

    //     // Already exists in variations
    //     if ($this->variations->contains($incomingDescription)) {
    //         return new NoChange($this);
    //     }

    //     // Add to variations
    //     $this->variations = $this->variations->add($incomingDescription);

    //     return new VariationAdded($this);
    // }
}
