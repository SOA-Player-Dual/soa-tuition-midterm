<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TuitionController;
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

Route::post('/verifyOTP', [TuitionController::class, 'verifyOTP']);
Route::post('/otp-send', [TuitionController::class, 'sendOTPMail']);
Route::post('/otp-resend', [TuitionController::class, 'resendOTPMail']);
