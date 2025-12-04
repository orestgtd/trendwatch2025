<?php 

namespace App\Domain\RealizedGain\Model;

interface GainBasis
{
    public function isRealized(): bool;
}