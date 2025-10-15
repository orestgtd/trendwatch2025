<?php

namespace App\Domain\Security\Outcome;

use App\Domain\Outcome\{
    Persistence\PersistenceIntent,
};

use App\Domain\Security\{
    Model\Security,
    Outcome\AbstractSecurityOutcome,
};

final class VariationAdded extends AbstractSecurityOutcome
{
    public function __construct(
        Security $security,
    ) {
        parent::__construct(
            $security,
            PersistenceIntent::update(['variations'])
        );
    }
}