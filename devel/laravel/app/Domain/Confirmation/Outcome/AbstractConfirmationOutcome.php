<?php

namespace App\Domain\Confirmation\Outcome;

use App\Domain\{
    Confirmation\Model\Confirmation,
    Outcome\AbstractOutcome,
    Outcome\OutcomeKind,
    Outcome\Persistence\PersistenceIntent,
};

abstract class AbstractConfirmationOutcome
extends AbstractOutcome
implements ConfirmationOutcome
{
    protected readonly Confirmation $confirmation;

    protected function __construct(
        Confirmation $confirmation,
        PersistenceIntent $persistenceIntent,
    ) {
        $this->confirmation = $confirmation;
        parent::__construct($persistenceIntent);
    }

    public function kind(): OutcomeKind
    {
        return OutcomeKind::CONFIRMATION;
    }

    public function getConfirmation(): Confirmation
    {
        return $this->confirmation;
    }
}
