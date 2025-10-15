<?php

namespace App\Domain\Security\Outcome;

use App\Domain\{
    Outcome\Outcome,
    Security\Model\Security,
};

interface SecurityOutcome extends Outcome
{
    public function getSecurity(): Security;
}
