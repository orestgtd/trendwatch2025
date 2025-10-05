<?php

use Illuminate\Support\Facades\Route;
// use Illuminate\Http\Request;

use \App\Presentation\Http\Controllers\{
    HelloController,
    PositionController,
    TradeConfirmationController
};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('beta')->group(function () {
    Route::get('/hello', [HelloController::class, 'index']);
    Route::get('/positions', [PositionController::class, 'index']);
    Route::post('/trades/import', [TradeConfirmationController::class, 'store']);
});
