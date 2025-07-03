<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/send-code', [AppController::class, 'sendVerificationCode']);
Route::post('/verify-code', [AppController::class, 'verifyCode']);
// Route::post('/logout', [AuthController::class, 'logout']); // إذا استخدمت JWT/Sanctum

