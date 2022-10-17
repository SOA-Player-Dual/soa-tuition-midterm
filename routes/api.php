<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TuitionController;
use App\Http\Controllers\OTPController;
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

Route::get('/get-tuition', [TuitionController::class, 'index']);

//Route::post('/otp/verify', [OTPController::class, 'verify']);
//Route::post('/otp/send', [OTPController::class, 'send']);
//Route::post('/otp/resend', [OTPController::class, 'resend']);

Route::prefix('otp')->group(function () {
    Route::post('/verify', [OTPController::class, 'verify']);
    Route::post('/send', [OTPController::class, 'send']);
    Route::post('/resend', [OTPController::class, 'resend']);
});
