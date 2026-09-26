<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StreamController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// Answer + ICE from browser viewer (no auth needed)
Route::post('/stream/answer',        [StreamController::class, 'sendAnswer']);
Route::post('/stream/ice-candidate', [StreamController::class, 'sendIceCandidate']);

// Protected routes (require login token)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout',       [AuthController::class, 'logout']);
    Route::post('/stream/offer', [StreamController::class, 'sendOffer']);
    Route::post('/stream/stop',  [StreamController::class, 'stopStream']);
    Route::get('/stream/history',[StreamController::class, 'history']);
});