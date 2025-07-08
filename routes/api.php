<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShipmentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/send-code', [AppController::class, 'sendVerificationCode']);
Route::post('/verify-code', [AppController::class, 'verifyCode']);
Route::post('/logout', [AuthController::class, 'logout']); 

Route::middleware('auth:sanctum')->post('/profile', [ProfileController::class, 'updateProfile']);

Route::post('/shipments', [ShipmentController::class, 'store']);


Route::middleware('auth:sanctum')->prefix('shipments')->group(function () {
    Route::delete('/{id}', [ShipmentController::class, 'destroy']);
});

Route::prefix('shipments')->group(function () {
    Route::get('/status/{status}', [ShipmentController::class, 'listByStatus']);
});



