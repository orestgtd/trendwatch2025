<?php

namespace App\Application\ProcessTradeConfirmation\Services;

use App\Application\ProcessTradeConfirmation\Dto\{
    ParsedTradeRequestDto,
    ParsedSecurityRequestDto,
};

use App\Application\ProcessTradeConfirmation\Services\{
    Parser\SecurityParser,
    Parser\TradeParser,
    PositionProcessor,
    RegistrationService,
    SecurityService,
    TradeService,
};
use App\Domain\{
   Confirmation\Model\Confirmation,
   Confirmation\Outcome\ConfirmationOutcome,
   Position\Outcome\PositionOutcome,
   Security\Outcome\SecurityOutcome,
};

use App\Foundation\Result;

final class TradeServiceCoordinator
{
   public function __construct(
      public readonly TradeService $tradeService,
      public readonly SecurityService $securityService,
      public readonly PositionProcessor $positionService,
      public readonly RegistrationService $registrationService,
      public readonly TradeParser $tradeParser,
      public readonly SecurityParser $securityParser,
   ) {}

   public function processConfirmationRequest(ParsedTradeRequestDto $parsed)
   {
      return $this->tradeService->processConfirmationRequest($parsed);
   }

   public function processSecurityRequest(ParsedSecurityRequestDto $parsed)
   {
      return $this->securityService->processSecurityRequest($parsed);
   }

   /** @return Result<PositionOutcome> */
   public function computePositionOutcome(Confirmation $confirmation): Result
   {
      return $this->positionService->computePositionOutcome($confirmation);
   }

   public function registerConfirmation(ConfirmationOutcome $outcome): self
   {
      $this->registrationService->registerConfirmation($outcome);

      return $this;
   }

   public function registerPosition(PositionOutcome $outcome): self
   {
      $this->registrationService->registerPosition($outcome);
      $this->registrationService->registerRealizedGainBasis($outcome->getRealizedGainOutcome());

      return $this;
   }

   public function registerSecurity(SecurityOutcome $outcome): self
   {
      $this->registrationService->registerSecurity($outcome);

      return $this;
   }

   public function persist()
   {
      $this->registrationService->persist();
   }
}
