<?php

namespace App\Application\ProcessTradeConfirmation;

use App\Application\ProcessTradeConfirmation\Services\TradeServiceCoordinator;

use App\Application\ProcessTradeConfirmation\Summary\{
    OutcomeSummary,
};

use App\Application\TradeConfirmation\{
    Dto\ParsedTradeData,
    TradeRequestParser,
};

use App\Domain\{
    Confirmation\Model\Confirmation,
    Confirmation\Outcome\ConfirmationOutcome,
    Position\Outcome\PositionOutcome,
    Security\Outcome\SecurityOutcome,
};

use App\Shared\Result;

final class ProcessTradeConfirmation
{
    public function __construct(
        private TradeServiceCoordinator $coordinator,
        private TradeRequestParser $parser,
    ) {}

    /** @return Result<OutcomeSummary> */
    public function handle(array $request): Result
    {
        // Step 1: Parse request
        $parseResult = $this->parseRequest($request);
        if ($parseResult->isFailure()) {
            return Result::failure($parseResult->getError());
        }

        /** @var ConfirmationOutcome $confirmationOutcome */
        /** @var SecurityOutcome $securityOutcome */
        [$confirmationOutcome, $securityOutcome] = $parseResult->getValue();

        // Step 2: Evaluate and update position
        $resultPositionOutcome = $this->coordinator->computePositionOutcome($confirmationOutcome->getConfirmation());
        if ($resultPositionOutcome->isFailure()) {
            return Result::failure($resultPositionOutcome->getError());
        }

        // Step 3: Register results
        $this->coordinator
            ->registerConfirmation($confirmationOutcome)
            ->registerPosition($resultPositionOutcome->getValue())
            ->registerSecurity($securityOutcome);


        // Step 4: Persist all registrations
        $this->coordinator->persist();

        // Step 5: Summarize and return
        return Result::success(
            new OutcomeSummary(
                $confirmationOutcome,
                $securityOutcome
            )
        );
    }

    /** @return Result<array<ConfirmationOutcome, SecurityOutcome>> */
    private function parseRequest(array $request): Result
    {
        $parseResult = $this->parser->parse($request);
        if ($parseResult->isFailure()) {
            return Result::failure($parseResult->getError());
        }

        $resultConfirmationOutcome = $parseResult->bind(
            fn (ParsedTradeData $parsed) => $this->coordinator->processConfirmationRequest($parsed->trade)
        );
        if ($resultConfirmationOutcome->isFailure()) {
            return Result::failure($resultConfirmationOutcome->getError());
        }

        $resultSecurityOutcome = $parseResult->bind(
            fn (ParsedTradeData $parsed) => $this->coordinator->processSecurityRequest($parsed->security)
        );
        if ($resultSecurityOutcome->isFailure()) {
            return Result::failure($resultSecurityOutcome->getError());
        }

        return Result::success([
            $resultConfirmationOutcome->getValue(),
            $resultSecurityOutcome->getValue(),
        ]);
    }
}
