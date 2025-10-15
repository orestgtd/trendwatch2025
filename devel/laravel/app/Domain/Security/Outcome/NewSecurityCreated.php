<?php

namespace App\Domain\Security\Outcome;

use App\Domain\Outcome\{
    Persistence\PersistenceIntent,
};

use App\Domain\Security\{
    Model\Security,
    Outcome\AbstractSecurityOutcome,
};

final class NewSecurityCreated extends AbstractSecurityOutcome
{
    public function __construct(
        Security $security,
    ) {
        parent::__construct(
            $security,
            PersistenceIntent::insertAll()
        );
    }
}
