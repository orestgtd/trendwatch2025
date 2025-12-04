<?php 

namespace App\Domain\RealizedGain\Model;

use App\Domain\RealizedGain\Model\GainBasis;



final class NoGainBasis
// implements GainBasis
{
    public function getThing(): mixed
    {
        /* No gain - no thing. */
        return null;
    }
}