<?php

namespace App\Infrastructure\Laravel\Eloquent;

use App\Domain\{
    Confirmation\Model\Confirmation,
    Outcome\Persistence\PersistenceAction,
    Position\Model\Position,
    Security\Model\Security,
};
use App\Domain\{
    Confirmation\Outcome\ConfirmationOutcome,
    Outcome\Outcome,
    Outcome\Persistence\PersistenceIntent,
    Position\Outcome\PositionOutcome,
    RealizedGain\Outcome\RealizedGainOutcome,
    Security\Outcome\SecurityOutcome,
};
use App\Domain\RealizedGain\Model\RealizedGainBasis;
use App\Infrastructure\Laravel\Eloquent\{
    Trade\Repositories\EloquentTradeRepository as TradeRepository,
    Position\Repositories\EloquentPositionRepository as PositionRepository,
    RealizedGain\Repositories\EloquentRealizedGainRepository as RealizedGainRepository,
    Security\Repositories\EloquentSecurityRepository as SecurityRepository,
};

use Illuminate\Support\Facades\DB;

class UnitOfWork
{
    private ?ConfirmationOutcome $confirmationOutcome = null;
    private ?SecurityOutcome $securityOutcome = null;
    private ?PositionOutcome $positionOutcome = null;
    private ?RealizedGainOutcome $realizedGainOutcome = null;

    public function __construct(
        private PositionRepository $positionRepository,
        private SecurityRepository $securityRepository,
        private TradeRepository $tradeRepository,
        private RealizedGainRepository $realizedGainRepository,
    ) {}

    public function withConfirmation(ConfirmationOutcome $outcome): self
    {
        $this->confirmationOutcome = $outcome;
        return $this;
    }

    public function withSecurity(SecurityOutcome $outcome): self
    {
        $this->securityOutcome = $outcome;
        return $this;
    }

    public function withPosition(PositionOutcome $outcome): self
    {
        $this->positionOutcome = $outcome;
        return $this;
    }

    public function withRealizedGainBasis(RealizedGainOutcome $outcome): self
    {
        $this->realizedGainOutcome = $outcome;
        return $this;
    }

    public function persist(): void
    {
        $maybeOutcome = function (Outcome $outcome, callable $persist) {

            $maybePersist = function (Outcome $outcome, callable $persist) {
                if ($outcome->requiresPersistence()) {
                    $persist($outcome);
                }
            };

            if ($outcome) {
                $maybePersist(
                    $outcome,
                    $persist,
                );
            }
        };


        DB::beginTransaction();

        $maybeOutcome(
            $this->confirmationOutcome,
            fn(ConfirmationOutcome $outcome) => $this->persistConfirmation(
                $outcome->getConfirmation(),
                $outcome->getPersistenceIntent()
            )
        );

        $maybeOutcome(
            $this->positionOutcome,
            fn(PositionOutcome $outcome) => $this->persistPosition(
                $outcome->getPosition(),
                $outcome->getPersistenceIntent()
            )
        );

        $maybeOutcome(
            $this->securityOutcome,
            fn(SecurityOutcome $outcome) => $this->persistSecurity(
                $outcome->getSecurity(),
                $outcome->getPersistenceIntent()
            )
        );

        $maybeOutcome(
            $this->realizedGainOutcome,
            fn(RealizedGainOutcome $outcome) => $this->persistRealizedGain(
                $outcome->getRealizedGainBasis(),
                $outcome->getPersistenceIntent()
            )
        );

        DB::commit();
    }

    public function rollback(): void
    {
        DB::rollBack();
    }

    private function persistConfirmation(Confirmation $confirmation, PersistenceIntent $intent): void
    {
        match ($intent->action->value) {
            PersistenceAction::INSERT->value => $this->tradeRepository->insert($confirmation),
        };
    }

    private function persistPosition(Position $position, PersistenceIntent $intent): void
    {
        match ($intent->action->value) {
            PersistenceAction::INSERT->value => $this->positionRepository->insert($position),
            PersistenceAction::UPDATE->value => $this->positionRepository->update($position, $intent->scope),
        };
    }

    private function persistRealizedGain(RealizedGainBasis $realizedGainBasis, PersistenceIntent $intent): void
    {
        match ($intent->action->value) {
            PersistenceAction::INSERT->value => $this->realizedGainRepository->insert($realizedGainBasis),
        };
    }

    private function persistSecurity(Security $security, PersistenceIntent $intent): void
    {
        match ($intent->action->value) {
            PersistenceAction::INSERT->value => $this->securityRepository->insert($security),
            PersistenceAction::UPDATE->value => $this->securityRepository->update($security, $intent->scope),
        };
    }
}
