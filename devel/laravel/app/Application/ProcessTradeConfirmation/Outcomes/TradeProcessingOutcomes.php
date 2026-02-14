<?php

namespace App\Application\ProcessTradeConfirmation\Outcomes;

use App\Application\ProcessTradeConfirmation\{
    Outcomes\TradeRequestOutcomes,
};

use App\Domain\{
    Confirmation\Outcome\ConfirmationOutcome,
    Position\Outcome\PositionOutcome,
    Security\Outcome\SecurityOutcome,
};

final class TradeProcessingOutcomes
{
    public function __construct(
        private readonly TradeRequestOutcomes $requestOutcomes,
        private readonly PositionOutcome $positionOutcome,
    ) {}

    public function getConfirmationOutcome(): ConfirmationOutcome
    {
        return $this->requestOutcomes->confirmationOutcome;
    }

    public function getSecurityOutcome(): SecurityOutcome
    {
        return $this->requestOutcomes->securityOutcome;
    }

    public function getPositionOutcome(): PositionOutcome
    {
        return $this->positionOutcome;
    }

    public function toArray(): array
    {
        return [
            'confirmation' => $this->requestOutcomes->confirmationOutcome,
            'security' => $this->requestOutcomes->securityOutcome,
            'position' => $this->positionOutcome,
        ];
    }
}
