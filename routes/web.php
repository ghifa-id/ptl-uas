<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('/authenticate', [LoginController::class, 'login'])->name('authenticate');
Route::post('/logout', [LoginController::class, 'logout'])->middleware(['auth'])->name('logout');

// Administrator Route
Route::prefix('administrator')->name('administrator.')->middleware(['auth', 'role:administrator'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'administrator'])->name('dashboard');
});

// Manager Route
Route::prefix('manager')->name('manager.')->middleware(['auth', 'role:manager'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'manager'])->name('dashboard');
});

// Applicant Route
Route::prefix('applicant')->name('applicant.')->middleware(['auth', 'role:applicant'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'applicant'])->name('dashboard');
});
