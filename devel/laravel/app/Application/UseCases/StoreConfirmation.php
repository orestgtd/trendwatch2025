<?php

namespace App\Application\UseCases;

use App\Application\Trade\{
    Dto\ParsedTradeRequestDto,
    Dto\ValidatedTradeDto,
    Services\TradeService,
};

use App\Application\Security\{
    Dto\ParsedSecurityRequestDto,
    Dto\ValidatedSecurityDto,
    Services\SecurityService,
};

use App\Application\Summary\{
    OutcomeSummary,
};

use App\Domain\{
    Confirmation\Outcome\ConfirmationOutcome,
};

use App\Domain\Security\{
    Outcome\SecurityOutcome,
};

use App\Infrastructure\Laravel\Eloquent\UnitOfWork;

use App\Shared\Result;

final class StoreConfirmation
{
    public function __construct(
        private TradeService $tradeService,
        private SecurityService $securityService,
        private UnitOfWork $uow,
     ) {}

    /** @return Result<OutcomeSummary> */
    public function handle(array $request): Result
    {
        $resultConfirmationOutcome = $this->tradeDtoFromData($request)
        ->bind(fn (ParsedTradeRequestDto $parsed) => $this->tradeService->processConfirmationRequest($parsed));

        if ($resultConfirmationOutcome->isFailure()) {
            return $resultConfirmationOutcome;
        }

        $resultSecurityOutcome = $this->securityDtoFromRequest($request)
        ->bind(fn (ParsedSecurityRequestDto $parsed) => $this->securityService->processSecurityRequest($parsed));

        if ($resultSecurityOutcome->isFailure()) {
            return $resultSecurityOutcome;
        }

        $resultConfirmationOutcome
        ->tap(fn (ConfirmationOutcome $outcome) => $this->registerConfirmationOutcome($outcome));

        $resultSecurityOutcome
        ->tap(fn (SecurityOutcome $outcome) => $this->registerSecurityOutcome($outcome));

        $this->uow->persist();

        return Result::success(
            new OutcomeSummary(
                $resultConfirmationOutcome->getValue(),
                $resultSecurityOutcome->getValue()
            )
        );
    }

    /** @return Result<ParsedSecurityRequestDto> */
    private function securityDtoFromRequest(array $data): Result
    {
        return
            ValidatedSecurityDto::fromArray($data)
            ->bind(
                fn (ValidatedSecurityDto $validatedDto): Result => ParsedSecurityRequestDto::fromValidatedSecurityDto($validatedDto)
            );
    }

    /** @return Result<ParsedTradeRequestDto> */
    private function tradeDtoFromData(array $data): Result
    {
        return
            ValidatedTradeDto::fromArray($data)
            ->bind(
                fn (ValidatedTradeDto $validatedDto): Result => ParsedTradeRequestDto::fromValidatedConfirmationDto($validatedDto)
            );
    }

    private function registerConfirmationOutcome(ConfirmationOutcome $outcome): void
    {
        if($outcome->requiresPersistence())
        {
            $this->uow->withConfirmation($outcome->getConfirmation());
        }
    }

    private function registerSecurityOutcome(SecurityOutcome $outcome): void
    {
        if($outcome->requiresPersistence())
        {
            $this->uow->withSecurity($outcome->getSecurity());
        }
    }
}