<?php

namespace App\Presentation\Http\Api\Controllers;

use Illuminate\Http\{
    JsonResponse,
    Request,
};

use App\Application\Procedures\ProcessTradeConfirmation\{
    Dto\ParsedTradeData,
    Outcomes\TradeProcessingOutcomes,
    ProcessTradeConfirmation,
};

use App\Domain\{
    Confirmation\Model\Confirmation,
};

use App\Presentation\{
    Parsers\TradeRequestParser,
};

// use App\Presentation\{
//     Http\Presenters\SummaryPresenter,
// };

final class TradeConfirmationController
{
    public function __construct(
        private ProcessTradeConfirmation $usecase,
        private TradeRequestParser $parser,
        // private SummaryPresenter $presenter,
    ) {}

    public function store(Request $request): JsonResponse
    {
        return $this->parser->parse($request->all())
            ->bind(
                fn (ParsedTradeData $parsed) => $this->usecase->handle($parsed)
            )
            ->match(
                fn (TradeProcessingOutcomes $outcome) => $this->success($outcome),
                fn (string $error) => $this->failure($error)
        );
    }

    private function success(TradeProcessingOutcomes $outcomes): JsonResponse
    {
        return response()->json([
                'status' => 'ok',
                'confirmation' => $this->mapConfirmation(
                    $outcomes
                        ->getConfirmationOutcome()
                        ->getConfirmation()
                ),
        ]);
    }

    private function failure(string $error, int $code = 422): JsonResponse
    {
        return response()->json(
            [
                'error' => $error,
            ],
            $code
        );
    }

    /** @return array<string, string> */
    private function mapConfirmation(Confirmation $confirmation): array
    {
        return [
            'trade_number' => (string) $confirmation->getTradeNumber(),
            'security_number' => (string) $confirmation->getSecurityNumber(),
            'symbol' => (string) $confirmation->getSymbol(),
            'description' => (string) $confirmation->getDescription(),
            'transaction_date' => (string) $confirmation->getTransactionDate(),
        ];
    }
}
