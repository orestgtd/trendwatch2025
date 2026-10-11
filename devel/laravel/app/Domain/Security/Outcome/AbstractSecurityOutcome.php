<?php

namespace App\Domain\Security\Outcome;

use App\Domain\{
    Outcome\AbstractOutcome,
    Outcome\OutcomeKind,
    Security\Model\Security,
};

abstract class AbstractSecurityOutcome
extends AbstractOutcome
implements SecurityOutcome
{
    protected readonly Security $security;

    protected function __construct(
        Security $security,
    ) {
        $this->security = $security;
    }

    public function kind(): OutcomeKind
    {
        return OutcomeKind::SECURITY;
    }

    public function getSecurity(): Security
    {
        return $this->security;
    }
}