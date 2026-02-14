<?php

namespace App\Application\ProcessTradeConfirmation\Outcomes;

use App\Domain\{
    Confirmation\Model\Confirmation,
    Confirmation\Outcome\ConfirmationOutcome,
    Security\Outcome\SecurityOutcome,
};

final class TradeRequestOutcomes
{
    public function __construct(
        public readonly ConfirmationOutcome $confirmationOutcome,
        public readonly SecurityOutcome $securityOutcome,
    ) {}

    public function getConfirmation(): Confirmation
    {
        return $this->confirmationOutcome->getConfirmation();
    }
}
