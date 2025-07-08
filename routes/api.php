<?php 
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Route; 
use App\Http\Controllers\AppController; 
use App\Http\Controllers\AuthController; 
use App\Http\Controllers\ProfileController; 
use App\Http\Controllers\ShipmentController; 
use App\Http\Controllers\FavoriteDestinationController; 
use App\Http\Controllers\OffersController; 

Route::middleware('auth:sanctum')->get('/user', function (Request $request) { return $request->user(); }); 
Route::post('/send-code', [AppController::class, 'sendVerificationCode']); 
Route::post('/verify-code', [AppController::class, 'verifyCode']); 
Route::post('/client/login', [AppController::class, 'login']); 
Route::post('/logout', [AuthController::class, 'logout']); 
Route::middleware('auth:sanctum')->group(function () { 
    Route::post('/profile', [ProfileController::class, 'updateProfile']); 
    Route::post('/favorite-destinations', [FavoriteDestinationController::class, 'store']); 
    Route::post('/shipments', [ShipmentController::class, 'store']); 
    Route::delete('/shipments/{id}', [ShipmentController::class, 'destroy']); 
    Route::get('/shipments/status/{status}', [ShipmentController::class, 'listByStatus']); 
    Route::post('/offers', [OffersController::class, 'store']); 
    Route::get('/shipments/{id}/offers', [OffersController::class, 'showOffersForShipment']); 
    Route::post('/offers/{id}/accept', [OffersController::class, 'acceptOffer']); });



