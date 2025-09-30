<?php

namespace App\Domain\Security\Outcome;

use App\Domain\Outcome\OutcomeKind;
use App\Domain\Security\Model\Security;

abstract class AbstractSecurityOutcome implements SecurityOutcome
{
    public function __construct(
        public readonly Security $security
    ) {}

    public function kind(): OutcomeKind
    {
        return OutcomeKind::SECURITY;
    }

    public function getSecurity(): Security
    {
        return $this->security;
    }
}