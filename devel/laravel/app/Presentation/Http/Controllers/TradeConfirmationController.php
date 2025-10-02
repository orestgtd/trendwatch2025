<?php

namespace App\Presentation\Http\Controllers;

use Illuminate\Http\{
    JsonResponse,
    Request,
};

use App\Application\ProcessTradeConfirmation\{
    Summary\OutcomeSummary,
    ProcessTradeConfirmation,
};

use App\Infrastructure\Laravel\Eloquent\{
    UnitOfWork,
};

use App\Presentation\Http\Presenters\SummaryPresenter;

final class TradeConfirmationController extends Controller
{
    public function __construct(
        private ProcessTradeConfirmation $usecase,
        private UnitOfWork $unitOfWork,
        private SummaryPresenter $presenter,
    ) {}

    public function store(Request $request): JsonResponse
    {
        return $this->usecase->handle($request->all())
        ->match(
            fn (OutcomeSummary $outcome) => $this->success($outcome),
            fn (string $error) => $this->failure($error)
        );
    }

    private function success(OutcomeSummary $summary): JsonResponse
    {
        return response()->json([
                'status' => 'ok',
                'thing' => $this->presenter->toArray(
                    $summary->confirmationOutcome,
                    $summary->securityOutcome
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
