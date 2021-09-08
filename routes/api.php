<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\ForgotPasswordApiController;
use App\Http\Controllers\Api\Auth\ResetPasswordApiController;
use App\Http\Controllers\Api\Auth\VerificationApiController;
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



Route::middleware('auth:sanctum')->get('user', function (Request $request) {
    return $request->user();
});

/* Auth::routes(['verify' => true]); */

Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('login', [AuthController::class, 'login'])->name('login');



Route::get('email/verify/{id}', [VerificationApiController::class, 'verify'])->name('verificationapi.verify');
Route::post('email/resend', [VerificationApiController::class, 'resend'])->name('verificationapi.resend');


Route::post('password/email', [ForgotPasswordApiController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('password/reset', [ResetPasswordApiController::class, 'showResetForm'])->name('password.reset');

Route::post('password/reset', [ResetPasswordApiController::class, 'reset'])->name('password.reset.post');

// Route::get('password/reset', [ForgotPasswordApiController::class, 'showLinkRequestForm'])->name('password.reset');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('get-user', [AuthController::class, 'getUser'])->name('getUser');
});