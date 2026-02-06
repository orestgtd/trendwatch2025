<?php

namespace App\Application\ProcessTradeConfirmation\Services;

use App\Domain\{
    Confirmation\Outcome\ConfirmationOutcome,
    Position\Outcome\PositionOutcome,
    RealizedGain\Outcome\RealizedGainOutcome,
    Security\Outcome\SecurityOutcome,
};

use App\Infrastructure\Laravel\Eloquent\UnitOfWork;

final class RegistrationService
{
    public function __construct(
        private UnitOfWork $uow
    ) {}

    public function registerConfirmation(ConfirmationOutcome $outcome): self
    {
        $this->uow->withConfirmation($outcome);
        return $this;
    }

    public function registerSecurity(SecurityOutcome $outcome): self
    {
        $this->uow->withSecurity($outcome);
        return $this;
    }

    public function registerPosition(PositionOutcome $outcome): self
    {
        $this->uow->withPosition($outcome);
        return $this;
    }

    public function registerRealizedGainBasis(RealizedGainOutcome $outcome): self
    {
        $this->uow->withRealizedGainBasis($outcome);
        return $this;
    }

    public function persist(): self
    {
        $this->uow->persist();
        return $this;
    }
}
