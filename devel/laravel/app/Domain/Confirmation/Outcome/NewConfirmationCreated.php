<?php

namespace App\Domain\Confirmation\Outcome;

use App\Domain\Confirmation\Model\Confirmation;

final class NewConfirmationCreated extends AbstractConfirmationOutcome
{
    public function __construct(
        Confirmation $confirmation,
    ) {
        parent::__construct(
            $confirmation,
        );
    }
}
