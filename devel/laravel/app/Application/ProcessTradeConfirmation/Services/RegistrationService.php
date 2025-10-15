<?php

namespace App\Application\ProcessTradeConfirmation\Services;

use App\Domain\{
    Confirmation\Outcome\ConfirmationOutcome,
    Position\Outcome\PositionOutcome,
    Security\Outcome\SecurityOutcome,
};

use App\Infrastructure\Laravel\Eloquent\UnitOfWork;

final class RegistrationService
{
    public function __construct(
        private UnitOfWork $uow
    ) {}

    public function registerConfirmation(ConfirmationOutcome $outcome): void
    {
        $this->uow->withConfirmation($outcome);
    }

    public function registerSecurity(SecurityOutcome $outcome): void
    {
        $this->uow->withSecurity($outcome);
    }

    public function registerPosition(PositionOutcome $outcome): void
    {
        $this->uow->withPosition($outcome);
    }

    public function persist(): void
    {
        $this->uow->persist();
    }
}
