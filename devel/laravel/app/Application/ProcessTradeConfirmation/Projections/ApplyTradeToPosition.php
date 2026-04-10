<?php

namespace App\Application\ProcessTradeConfirmation\Projections;

use App\Application\Contracts\{
    PositionRepositoryContract,
    RealizedGainRepositoryContract,
};

use App\Application\ProcessTradeConfirmation\{
    Events\RealizedGainCreated,
    Events\TradeConfirmationCreated,
    Lookups\PositionLookup,
};

use App\Domain\Position\{
    Model\Position,
    Outcome\DecreasedHolding,
    Outcome\IncreasedHolding,
    Outcome\NewPositionCreated,
    Outcome\PositionOutcome,
    PositionManager,
};

use App\Domain\RealizedGain\{
    Outcome\NewRealizedGainCreated,
    Outcome\NoRealizedGain,
    Outcome\RealizedGainOutcome,
};

final class ApplyTradeToPosition
{
    public function __construct(
        private readonly PositionLookup $lookup,
        private readonly PositionRepositoryContract $position_repository,
        private readonly RealizedGainRepositoryContract $realized_gain_repository,
        private PositionManager $manager,

    ) {}

    public function handle(TradeConfirmationCreated $event): void
    {
        $confirmation = $event->confirmation;

        $this->lookup->matchBySecurityNumber(
            $confirmation->getSecurityNumber(),
            onNotFound: fn() => $this->manager->createPosition($confirmation),
            onExists: fn(Position $position) => $confirmation->matchPositionEffect(
                onOpen: fn() => $this->manager->increasePosition($confirmation, $position),
                onClose: fn() => $this->manager->decreasePosition($confirmation, $position)
            )
        )->onSuccess(
            fn (PositionOutcome $outcome) => $this->tapOutcome($outcome)
        );
    }

    private function tapOutcome(PositionOutcome $outcome): void
    {
        $this->persistOutcome($outcome);
        $this->processRealizedGainOutcome($outcome->getRealizedGainOutcome());
    }

    private function persistOutcome(PositionOutcome $outcome): void
    {
        $position = $outcome->getPosition();

        match(get_class($outcome)) {
            DecreasedHolding::class => $this->updatePosition($position),
            IncreasedHolding::class => $this->updatePosition($position),
            NewPositionCreated::class => $this->position_repository->insert($position),
        };
    }

    private function updatePosition(Position $position): void
    {
        $this->position_repository->update(
            $position->getSecurityNumber(),
            $position->getPositionQuantity(),
            $position->getTotalCost()
        );
    }

    private function processRealizedGainOutcome(RealizedGainOutcome $outcome): void
    {
        match(get_class($outcome)) {
            NoRealizedGain::class => null,
            NewRealizedGainCreated::class => $this->realized_gain_repository->insert($outcome->getRealizedGainBasis()),
        };
    }
}
