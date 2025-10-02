<?php

namespace App\Application\ProcessTradeConfirmation;

use App\Application\ProcessTradeConfirmation\{
    Dto\ParsedSecurityRequestDto,
    Dto\ParsedTradeRequestDto,
};

use App\Application\ProcessTradeConfirmation\Services\{
    Parser\SecurityParser,
    Parser\TradeParser,
    PositionService,
    RegistrationManager,
    SecurityService,
    TradeService,
};

use App\Application\Summary\{
    OutcomeSummary,
};

use App\Domain\{
    Confirmation\Outcome\ConfirmationOutcome,
    Security\Outcome\SecurityOutcome,
};

use App\Shared\Result;

final class ProcessTradeConfirmation
{
    public function __construct(
        private TradeService $tradeService,
        private SecurityService $securityService,
        private PositionService $positionService,
        private RegistrationManager $registrationManager,
        private TradeParser $tradeParser,
        private SecurityParser $securityParser,
     ) {}

    /** @return Result<OutcomeSummary> */
    public function handle(array $request): Result
    {
        $resultConfirmationOutcome = $this->tradeParser->parse($request)
            ->bind(fn(ParsedTradeRequestDto $parsed) => $this->tradeService->processConfirmationRequest($parsed));

        if ($resultConfirmationOutcome->isFailure()) {
            return $resultConfirmationOutcome;
        }

       $resultSecurityOutcome = $this->securityParser->parse($request)
            ->bind(fn(ParsedSecurityRequestDto $parsed) => $this->securityService->processSecurityRequest($parsed));

        if ($resultSecurityOutcome->isFailure()) {
            return $resultSecurityOutcome;
        }

       $resultPositionOutcome = $resultConfirmationOutcome
            ->bind(fn(ConfirmationOutcome $confirmationOutcome) =>
                $this->positionService->updatePosition($confirmationOutcome)
            );

        $resultConfirmationOutcome
        ->tap(fn (ConfirmationOutcome $outcome) => $this->registrationManager->registerConfirmation($outcome));

        $resultSecurityOutcome
        ->tap(fn (SecurityOutcome $outcome) => $this->registrationManager->registerSecurity($outcome));

        $this->registrationManager->persist();

        return Result::success(
            new OutcomeSummary(
                $resultConfirmationOutcome->getValue(),
                $resultSecurityOutcome->getValue()
            )
        );
    }
}
