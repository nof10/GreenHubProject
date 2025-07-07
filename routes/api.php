<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppController;
use App\Http\Controllers\ProfileController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/send-code', [AppController::class, 'sendVerificationCode']);
Route::post('/verify-code', [AppController::class, 'verifyCode']);
// Route::post('/logout', [AuthController::class, 'logout']); // إذا استخدمت JWT/Sanctum

<<<<<<< HEAD
Route::middleware('auth:sanctum')->put('/profile', [ProfileController::class, 'updateProfile']);
=======
Route::middleware('auth:sanctum')->post('/profile', [ProfileController::class, 'updateProfile']);
Route::middleware('auth:sanctum')->put('/driver/update-profile', [ProfileController::class, 'updateDriverProfile']);
>>>>>>> f791d7af1e342496b42471251f7c598b59005456

