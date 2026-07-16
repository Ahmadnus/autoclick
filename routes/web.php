<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LicenseController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
});

Route::middleware(['auth', 'role:Super Admin|Manager'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('licenses')->name('licenses.')->group(function () {
        Route::get('/', [LicenseController::class, 'index'])->name('index');
        Route::get('/create', [LicenseController::class, 'create'])->name('create');
        Route::post('/', [LicenseController::class, 'store'])->name('store');
        Route::patch('/{license}/activate', [LicenseController::class, 'activate'])->name('activate');
        Route::patch('/{license}/block', [LicenseController::class, 'block'])->name('block');
        Route::patch('/{license}/extend', [LicenseController::class, 'extend'])->name('extend');

        // Deleting a device record is destructive enough to reserve for
        // Super Admin only — Managers can activate/block/extend but not
        // permanently remove a driver's record.
        Route::middleware('role:Super Admin')
            ->delete('/{license}', [LicenseController::class, 'destroy'])
            ->name('destroy');
    });
});
