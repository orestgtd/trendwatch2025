<?php

namespace App\Presentation\Http\Controllers;

use Illuminate\Http\{
    JsonResponse,
    Request,
};

use App\Application\{
    UseCases\StoreConfirmation,
};

use App\Domain\Security\{
    Context\SecurityContextWithOutcome,
    Model\Security,
};

use App\Infrastructure\Laravel\Eloquent\{
    UnitOfWork,
};
use App\Presentation\Http\Presenters\SecurityPresenter;

final class StoreConfirmationController extends Controller
{
    public function __construct(
        private StoreConfirmation $usecase,
        private UnitOfWork $unitOfWork
    ) {}

    public function store(Request $request): JsonResponse
    {
        $response =
            $this->usecase->handle($request->all())
            ->match(
                fn (SecurityContextWithOutcome $context) => $this->success($context->security),
                fn (string $error) => $this->failure($error, 422)
            )
            ;

        return $response;
    }

    private function success(Security $security): JsonResponse
    {
        return response()->json([
                'status' => 'ok',
                'security' => SecurityPresenter::toArray($security),
        ]);
    }

    private function failure(string $error, int $code): JsonResponse
    {
        return response()->json(
            [
                'error' => $error,
            ],
            $code
        );
    }
}
