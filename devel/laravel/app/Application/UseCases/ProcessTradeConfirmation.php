<?php

namespace App\Application\UseCases;

use App\Application\Security\{
    Dto\ParsedSecurityRequestDto,
    Services\SecurityService,
};

use App\Application\Services\{
    Parser\SecurityParser,
    Parser\TradeParser,
    PositionService,
    RegistrationManager,
};

use App\Application\Summary\{
    OutcomeSummary,
};

use App\Application\Trade\{
    Dto\ParsedTradeRequestDto,
    Services\TradeService,
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
