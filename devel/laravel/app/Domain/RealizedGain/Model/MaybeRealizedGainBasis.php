<?php 

namespace App\Domain\RealizedGain\Model;

use App\Domain\RealizedGain\{
    Model\RealizedGainBasis,
    Outcome\NewRealizedGainCreated,
    Outcome\NoRealizedGain,
    Outcome\RealizedGainOutcome,
};

final class MaybeRealizedGainBasis
{
    private ?RealizedGainBasis $realizedGainBasis;

    private function __construct(?RealizedGainBasis $realizedGainBasis)
    {
        $this->realizedGainBasis = $realizedGainBasis;
    }

    public static function create(?RealizedGainBasis $realizedGainBasis = null): self
    {
        return new self($realizedGainBasis);
    }

    public function tap(callable $action): void
    {
        if ($this->realizedGainBasis)
        {
            $action($this->realizedGainBasis);
        }
    }

    // public function getOutcome(): RealizedGainOutcome
    // {
    //     return $this->realizedGainBasis
    //     ? new NewRealizedGainCreated($this->realizedGainBasis)
    //     : new NoRealizedGain ();
    // }

    public function hasRealizedGain(): bool
    {
        return ! is_null($this->realizedGainBasis);
    }
}