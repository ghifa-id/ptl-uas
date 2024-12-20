<?php

use App\Http\Controllers\Administrator\DepartmentController;
use App\Http\Controllers\Administrator\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Route::get('/', [LoginController::class, 'index'])->name('login');


Route::get('/', function () {
    $roles = [
        'staff' => 'applicant',
        'kasubag' => 'manager',
        'bendahara' => 'administrator',
    ];
    if (Auth::check()) {
        return redirect()->route($roles[Auth::user()->role] . '.dashboard');
    } else {
        return view('auth.login');
    }
})->name('login');

Route::post('/authenticate', [LoginController::class, 'login'])->name('authenticate');
Route::post('/logout', [LoginController::class, 'logout'])->middleware(['auth'])->name('logout');

Route::get('/auth-check', function () {
    if (Auth::check()) {
        return response()->json([
            'message' => 'User is authenticated',
            'user' => Auth::user(),
        ]);
    }
    return response()->json(['message' => 'User is not authenticated']);
});

// Administrator Route
Route::prefix('administrator')->name('administrator.')->middleware(['auth', 'role:administrator'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'administrator'])->name('dashboard');

    Route::prefix('department')->name('department.')->controller(DepartmentController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/data-table', 'datatable')->name('datatable');
        Route::post('/store', 'store')->name('store');
        Route::put('/update', 'update')->name('update');
        Route::delete('/delete', 'destroy')->name('destroy');
    });

    Route::prefix('user')->name('user.')->controller(UserController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/data-table', 'datatable')->name('datatable');
        Route::post('/store', 'store')->name('store');
        Route::put('/update', 'update')->name('update');
        Route::delete('/delete', 'destroy')->name('destroy');
    });
});

// Manager Route
Route::prefix('manager')->name('manager.')->middleware(['auth', 'role:manager'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'manager'])->name('dashboard');
});

// Applicant Route
Route::prefix('applicant')->name('applicant.')->middleware(['auth', 'role:applicant'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'applicant'])->name('dashboard');
});
