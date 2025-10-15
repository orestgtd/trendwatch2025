<?php

namespace App\Domain\Confirmation\Outcome;

use App\Domain\Confirmation\Model\Confirmation;

use App\Domain\Outcome\Persistence\PersistenceIntent;

final class NewConfirmationCreated extends AbstractConfirmationOutcome
{
    public function __construct(
        Confirmation $confirmation,
    ) {
        parent::__construct(
            $confirmation,
            PersistenceIntent::insertAll()
        );
    }
}
