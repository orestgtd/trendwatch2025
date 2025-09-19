<?php

namespace App\Application\UseCases;

use App\Application\Security\{
    Dto\ParsedSecurityRequestDto,
    Dto\ValidatedSecurityDto,
    Services\SecurityService,
};

use App\Domain\Security\{
    Outcome\SecurityOutcome,
};

use App\Infrastructure\Laravel\Eloquent\UnitOfWork;

use App\Shared\Result;

final class StoreConfirmation
{
    public function __construct(
        private SecurityService $securityService,
        private UnitOfWork $uow,
     ) {}

    /** @return Result<SecurityContextWithOutcome> */
    public function handle(array $data): Result
    {
        return $this->securityDtoFromData($data)
            ->bind(fn (ParsedSecurityRequestDto $parsed) => $this->securityService->processSecurityRequest($parsed))
            ->tap(fn(SecurityOutcome $outcome) => $this->registerSecurityOutcome($outcome))
            ->tap(fn() => $this->uow->persist())
        ;
    }

    /** @return Result<ParsedSecurityRequestDto> */
    private function securityDtoFromData(array $data): Result
    {
        return
            ValidatedSecurityDto::fromArray($data)
            ->bind(
                fn (ValidatedSecurityDto $validatedDto): Result => ParsedSecurityRequestDto::fromValidatedSecurityDto($validatedDto)
            );
    }

    private function registerSecurityOutcome(SecurityOutcome $outcome): void
    {
        if($outcome->requiresPersistence())
        {
            $this->uow->withSecurity($outcome->security);
        }
    }
}