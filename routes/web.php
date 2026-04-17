<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\TypeOfServiceController;
use App\Http\Controllers\TransOrderController;
use App\Http\Controllers\TransLaundryPickupController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\VoucherController;

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware('auth')->group(function () {
    // Dashboard accessible by all logged in users
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Admin Routes
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
        Route::resource('services', TypeOfServiceController::class)->parameters(['services' => 'service'])->except(['show']);
        Route::resource('vouchers', VoucherController::class)->except(['show']);
        Route::post('/vouchers/check', [VoucherController::class, 'checkVoucher'])->name('vouchers.check');
    });

    // Operator Routes
    Route::middleware('role:operator,admin')->group(function () {
        Route::resource('customers', CustomerController::class)->except(['show']);
        Route::resource('orders', TransOrderController::class);
        Route::patch('/orders/{order}/status', [TransOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    });

    // Pimpinan Routes
    Route::middleware('role:pimpinan,admin')->group(function () {
        Route::get('/report', [ReportController::class, 'index'])->name('report.index');
    });
});
