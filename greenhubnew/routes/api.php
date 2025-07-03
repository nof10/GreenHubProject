<?php
use App\Http\Controllers\AppController;

Route::post('/send-code', [AppController::class, 'sendVerificationCode']);
Route::post('/verify-code', [AppController::class, 'verifyCode']);
// Route::post('/logout', [AuthController::class, 'logout']); // إذا استخدمت JWT/Sanctum