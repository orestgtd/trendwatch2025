<?php

namespace App\Application\TradeConfirmation;

use App\Domain\{
    Confirmation\Outcome\ConfirmationOutcome,
    Security\Outcome\SecurityOutcome,
};

final class TradeProcessingOutcomes
{
    public function __construct(
        public readonly ConfirmationOutcome $confirmationOutcome,
        public readonly SecurityOutcome $securityOutcome,
    ) {}
}