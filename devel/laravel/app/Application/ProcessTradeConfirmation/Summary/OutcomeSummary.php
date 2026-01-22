<?php

namespace App\Application\ProcessTradeConfirmation\Summary;

use App\Domain\{
    Confirmation\Model\Confirmation,
    Confirmation\Outcome\ConfirmationOutcome,
    Position\Outcome\PositionOutcome,
    Security\Outcome\SecurityOutcome,
};

final class OutcomeSummary
{
    public readonly ConfirmationOutcome $confirmationOutcome;
    public readonly SecurityOutcome $securityOutcome;
    public PositionOutcome $positionOutcome;

    public function __construct(
        ConfirmationOutcome $confirmationOutcome,
        SecurityOutcome $securityOutcome,
    ) {
        $this->confirmationOutcome = $confirmationOutcome;
        $this->securityOutcome = $securityOutcome;
    }

    public function withPositionOutcome(PositionOutcome $positionOutcome): self
    {
        $this->positionOutcome = $positionOutcome;

        return $this;
    }

    public function getConfirmation(): Confirmation
    {
        return $this->confirmationOutcome->getConfirmation();
    }

    public function toArray(): array
    {
        return [
            'confirmation' => $this->confirmationOutcome,
            'security' => $this->securityOutcome,
            'position' => $this->positionOutcome,
        ];
    }
}
