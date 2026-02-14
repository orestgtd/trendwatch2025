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

use App\Presentation\Http\Presenters\SummaryPresenter;

final class TradeConfirmationController extends Controller
{
    public function __construct(
        private ProcessTradeConfirmation $usecase,
        private SummaryPresenter $presenter,
    ) {}

    public function store(Request $request): JsonResponse
    {
        return $this->usecase->handle($request->all())
        ->match(
            fn (TradeProcessingOutcomes $outcomes) => $this->success($outcomes),
            fn (string $error) => $this->failure($error)
        );
    }

    private function success(TradeProcessingOutcomes $outcomes): JsonResponse
    {
        return response()->json([
                'status' => 'ok',
                'thing' => $this->presenter->toArray(
                    $outcomes->getConfirmationOutcome(),
                    $outcomes->getSecurityOutcome(),
                    $outcomes->getPositionOutcome()
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
}
