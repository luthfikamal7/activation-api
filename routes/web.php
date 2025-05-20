<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivationKeyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SerialKeyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;

// Public routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Routes that require authentication
Route::middleware('auth')->group(function () {
    // Route::get('/dashboard', function () {return view('dashboard.dashboard');})->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


    // Show customer list
    Route::get('/customers', [CustomerController::class, 'list'])->name('customers.list');

    // Show customer registration form
    Route::get('/register-customer', [CustomerController::class, 'showForm'])->name('customer.register.form');

    // Register customer
    Route::post('/register-customer', [CustomerController::class, 'register'])->name('customer.register');

    // List customers
    Route::get('/edit-customer/{id}', [CustomerController::class, 'edit'])->name('customer.edit');

    // Update customer
    Route::put('/edit-customer/{id}', [CustomerController::class, 'update'])->name('customer.update');

    // Delete customer
    Route::delete('/delete-customer/{id}', [CustomerController::class, 'destroy'])->name('customer.delete');



     // Show project list
    Route::get('/projects', [ProjectController::class, 'list'])->name('project.list');

    // Show project registration form
    Route::get('/register-project', [ProjectController::class, 'showForm'])->name('project.register.form');

    // Register project
    Route::post('/register-project', [ProjectController::class, 'register'])->name('project.register');

    // Edit project
    Route::get('/edit-project/{id}', [ProjectController::class, 'edit'])->name('project.edit');

    // Update project
    Route::put('/edit-project/{id}', [ProjectController::class, 'update'])->name('project.update');

    // Delete project
    Route::delete('/delete-project/{id}', [ProjectController::class, 'destroy'])->name('project.delete');




    // Show activation key list
    Route::get('/serial-keys', [SerialKeyController::class, 'list'])->name('serial.key.list');

    // Show activation key generation form
    Route::get('/generate-key', [ActivationKeyController::class, 'showForm'])->name('activation.form');

    // Generate activation key
    Route::post('/generate-key', [ActivationKeyController::class, 'generate'])->name('activation.generate');

    // Edit project
    Route::get('/edit-serial/{id}', [SerialKeyController::class, 'edit'])->name('serial.key.edit');

    // Update project
    Route::put('/edit-serial/{id}', [SerialKeyController::class, 'update'])->name('serial.key.update');

    // Delete project
    Route::delete('/delete-serial/{id}', [SerialKeyController::class, 'destroy'])->name('serial.key.delete');


    

    Route::get('/register-project', [ProjectController::class, 'showForm'])->name('project.register.form');
    Route::post('/register-project', [ProjectController::class, 'register'])->name('project.register');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
