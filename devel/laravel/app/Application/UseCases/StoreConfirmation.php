<?php

namespace App\Application\UseCases;

use App\Application\Security\{
    Dto\ParsedSecurityDto,
    Dto\ValidatedSecurityDto,
    Services\SecurityService,
};

use App\Domain\Security\{
    Context\SecurityContextWithOutcome,
};

use App\Infrastructure\Laravel\Eloquent\UnitOfWork;

use App\Shared\Result;
use Illuminate\Auth\Events\Validated;

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
            ->bind(fn (ParsedSecurityDto $parsed) => $this->securityService->processSecurityRequestDto($parsed))
            ->tap(fn(SecurityContextWithOutcome $context) => $this->processOutcome($context))
            ->tap(fn() => $this->uow->persist())
        ;
    }

    /** @return Result<ParsedSecurityDto> */
    private function securityDtoFromData(array $data): Result
    {
        return
            ValidatedSecurityDto::fromArray($data)
            ->bind(
                fn (ValidatedSecurityDto $validatedDto): Result => ParsedSecurityDto::fromValidatedSecurityDto($validatedDto)
            );
    }

    private function processOutcome(SecurityContextWithOutcome $context): void
    {
        if($context->outcome->requiresPersistence())
        {
            $this->uow->withSecurity($context->security);
        }
    }
}