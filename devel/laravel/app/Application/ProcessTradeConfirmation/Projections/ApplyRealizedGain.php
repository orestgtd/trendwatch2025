<?php

namespace App\Application\ProcessTradeConfirmation\Projections;

use App\Application\Contracts\{
    SecurityRepositoryContract,
};

use App\Application\ProcessTradeConfirmation\{
    Events\TradeConfirmationCreated,
    Lookups\SecurityLookup,
};
use App\Application\ProcessTradeConfirmation\Events\RealizedGainCreated;
use App\Domain\Security\{
    Model\Security,
    Outcome\NewSecurityCreated,
    Outcome\NoChange,
    Outcome\SecurityOutcome,
    Model\EquitySecurity,
    Model\OptionSecurity,
    ValueObjects\Description,
    ValueObjects\SecurityInfo,
    ValueObjects\Variations\NoVariations,
};
use App\Domain\Security\Outcome\VariationAdded;
use App\Foundation\Result;

final class ApplyRealizedGain
{
    public function __construct(
        // private readonly SecurityLookup $lookup,
        // private readonly SecurityRepositoryContract $repository,
    ) {}

    public function handle(RealizedGainCreated $event): void
    {
        dd($event);
    
        // $confirmation = $event->confirmation;

        // $securityInfo = $confirmation->getSecurityInfo();
        // $description = $securityInfo->canonicalDescription;

        // $this->lookup->matchBySecurityNumber(
        //     $securityInfo->securityNumber,
        //     onNotFound: fn() => $this->createNewSecurity($securityInfo),
        //     onExists: fn(Security $security) => $this->updateExistingSecurity($security, $description)
        // )->onSuccess(
        //     fn(SecurityOutcome $outcome) => $this->persistOutcome($outcome)
        // );
    }

    // /** @return Result<SecurityOutcome> */
    // private function createNewSecurity(SecurityInfo $securityInfo): Result
    // {
    //     return Result::success(new NewSecurityCreated(
    //         $securityInfo->unitType->delegate(
    //             onContracts: fn () => OptionSecurity::create($securityInfo, NoVariations::create()),
    //             onShares: fn () => EquitySecurity::create($securityInfo, NoVariations::create())
    //         )
    //     ));
    // }

    // /** @return Result<SecurityOutcome> */
    // private function updateExistingSecurity(Security $security, Description $description): Result
    // {
    //     return Result::success($security->recordDescription($description));
    // }

    // private function persistOutcome(SecurityOutcome $outcome): void
    // {
    //     match(get_class($outcome)) {
    //         NoChange::class => null,
    //         NewSecurityCreated::class => $this->repository->insert($outcome->getSecurity()),
    //         VariationAdded::class => $this->repository->updateVariations(
    //             $outcome->getSecurity()->getSecurityNumber(),
    //             $outcome->getSecurity()->getVariations()),
    //     };
    // }
}
