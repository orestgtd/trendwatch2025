<?php

namespace App\Application\Summary;

use App\Domain\{
    Confirmation\Outcome\ConfirmationOutcome,
    Security\Outcome\SecurityOutcome,
};

final class OutcomeSummary
{
    public readonly ConfirmationOutcome $confirmationOutcome;
    public readonly SecurityOutcome $securityOutcome;

    public function __construct(
        ConfirmationOutcome $confirmationOutcome,
        SecurityOutcome $securityOutcome,
    ) {
        $this->confirmationOutcome = $confirmationOutcome;
        $this->securityOutcome = $securityOutcome;
    }

    public function toArray(): array
    {
        return [
            'confirmation' => $this->confirmationOutcome,
            'security' => $this->securityOutcome,
        ];
    }
}
