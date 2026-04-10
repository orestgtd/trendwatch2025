<?php

namespace App\Presentation\Http\Controllers;

use Illuminate\Http\{
    JsonResponse,
    Request,
};

use App\Application\ProcessTradeConfirmation\{
    Outcomes\TradeProcessingOutcomes,
    ProcessTradeConfirmation,
};

use App\Application\TradeConfirmation\{
    Dto\ParsedTradeData,
    TradeRequestParser,
};

// use App\Presentation\{
//     Http\Presenters\SummaryPresenter,
// };

final class TradeConfirmationController extends Controller
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

    private function success(TradeProcessingOutcomes $outcome): JsonResponse
    {
        return response()->json([
                'status' => 'ok',
                'confirmation' => $outcome,
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
}
