<?php

namespace App\Domain\Security\Outcome;

use App\Domain\{
    Outcome\AbstractOutcome,
    Outcome\OutcomeKind,
    Outcome\Persistence\PersistenceIntent,
    Security\Model\Security,
};

abstract class AbstractSecurityOutcome
extends AbstractOutcome
implements SecurityOutcome
{
    protected readonly Security $security;

    protected function __construct(
        Security $security,
        PersistenceIntent $persistenceIntent,
    ) {
        $this->security = $security;
        parent::__construct($persistenceIntent);
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