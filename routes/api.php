<?php

use App\Http\Controllers\Api\ContractController;
use App\Http\Controllers\AuthenticateController;
use App\Http\Controllers\Api\LoanTermController;
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

Route::group(['prefix' => '/', 'middleware' => 'auth:sanctum'],function() {
    Route::group(['prefix' => 'me'],function() {
        Route::get('/', [AuthenticateController::class, 'me'])->name('api.user.me');
        Route::get('contracts', [ContractController::class, 'byCustomer'])->name('api.user.me.contracts');
        Route::post('contracts', [ContractController::class, 'store'])->name('api.loan-terms.store');
    });
    Route::group(['prefix' => 'loan-terms'],function() {
        Route::get('/', [LoanTermController::class, 'index'])->name('api.loan-terms.index');
        Route::post('/', [LoanTermController::class, 'store'])->name('api.loan-terms.store');
    });
    Route::group(['prefix' => 'contracts'],function() {
        Route::get('/', [ContractController::class, 'index'])->name('api.user.me.index');
        Route::get('/{contract}', [ContractController::class, 'detail'])->name('api.user.me.detail');
        Route::get('/{contract}/status', [ContractController::class, 'status'])->name('api.user.me.status');
        Route::get('/{contract}/repayments', [ContractController::class, 'repayments'])->name('api.user.me.repayments');
        Route::put('/{contract}/pay', [ContractController::class, 'pay'])->name('api.user.me.pay');
    });

});