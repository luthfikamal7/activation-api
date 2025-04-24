<?php

use App\Http\Controllers\ActivationController;
use Illuminate\Support\Facades\Route;

Route::post('/validate-serial', [ActivationController::class, 'validateSerial']);
