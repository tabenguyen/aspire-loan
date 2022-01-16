<?php

use App\Http\Controllers\AuthenticateController;
use App\Http\Controllers\LoanTermController;
use Illuminate\Support\Facades\Route;

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
Route::post('login', [AuthenticateController::class, 'login'])->name('login');

Route::prefix('/')->middleware('auth:sanctum')->group(function() {
    Route::get('me', [AuthenticateController::class, 'me'])->name('api.user.me');
    Route::prefix('loan-terms')->group(function() {
        Route::get('/', [LoanTermController::class, 'index'])->name('api.loan-terms.index');
        Route::post('/', [LoanTermController::class, 'store'])->name('api.loan-terms.store');
    });
});