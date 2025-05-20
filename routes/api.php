<?php

use App\Http\Controllers\ActivationController;
use App\Http\Controllers\ActivationUpdateController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'user']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/validate-serial', [ActivationController::class, 'validateSerial']);
Route::post('/validate-serial-update', [ActivationUpdateController::class, 'validateSerial']);

