<?php

use App\Http\Controllers\AuthenticateController;
use Illuminate\Http\Request;
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
Route::middleware('auth:sanctum')->get('me', [AuthenticateController::class, 'me'])->name('user.me');