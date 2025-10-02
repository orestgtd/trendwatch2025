<?php

namespace App\Application\ProcessTradeConfirmation\Services;

use App\Domain\Confirmation\Outcome\ConfirmationOutcome;
use App\Domain\Security\Outcome\SecurityOutcome;
use App\Infrastructure\Laravel\Eloquent\UnitOfWork;

final class RegistrationManager
{
    public function __construct(
        private UnitOfWork $uow
    ) {}

    public function registerConfirmation(ConfirmationOutcome $outcome): void
    {
        if ($outcome->requiresPersistence()) {
            $this->uow->withConfirmation($outcome->getConfirmation());
        }
    }

    public function registerSecurity(SecurityOutcome $outcome): void
    {
        if ($outcome->requiresPersistence()) {
            $this->uow->withSecurity($outcome->getSecurity());
        }
    }

    public function persist(): void
    {
        $this->uow->persist();
    }
}
