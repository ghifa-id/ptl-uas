<?php

use App\Http\Controllers\Administrator\DepartmentController as AdministratorDepartmentController;
use App\Http\Controllers\Administrator\UserController as AdministratorUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Administrator\DashboardController as AdministratorDashboardController;
use App\Http\Controllers\Manager\DashboardController as ManagerDashboardController;
use App\Http\Controllers\Applicant\DashboardController as ApplicantDashboardController;
use App\Http\Controllers\Superuser\DashboardController as SuperuserDashboardController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Superuser\DepartmentController as SuperuserDepartmentController;
use App\Http\Controllers\Superuser\UserController as SuperuserUserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Route::get('/', [LoginController::class, 'index'])->name('login');


Route::get('/', function () {
    $roles = config('roles');
    if (Auth::check()) {
        return redirect()->route($roles[Auth::user()->role] . '.dashboard.index');
    } else {
        return view('auth.login');
    }
})->name('login');

Route::post('/authenticate', [LoginController::class, 'login'])->name('authenticate');
Route::post('/logout', [LoginController::class, 'logout'])->middleware(['auth'])->name('logout');

Route::prefix('password')->name('password.')->controller(PasswordController::class)->group(function () {
    Route::get('/first-change', 'firstChange')->name('first.change');
    Route::post('/update', 'updatePassword')->name('update');
    Route::get('/skip', 'skipChange')->name('skipchange');
});

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
    Route::prefix('dashboard')->name('dashboard.')->controller(AdministratorDashboardController::class)->group(function () {
        Route::get('/', 'index')->name('index');
    });

    Route::prefix('department')->name('department.')->controller(AdministratorDepartmentController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/data-table', 'datatable')->name('datatable');
        Route::post('/store', 'store')->name('store');
        Route::put('/update', 'update')->name('update');
        Route::delete('/delete', 'destroy')->name('destroy');
    });

    Route::prefix('user')->name('user.')->controller(AdministratorUserController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/data-table', 'datatable')->name('datatable');
        Route::post('/store', 'store')->name('store');
        Route::put('/update', 'update')->name('update');
        Route::delete('/delete', 'destroy')->name('destroy');
    });
});

// Manager Route
Route::prefix('manager')->name('manager.')->middleware(['auth', 'role:manager'])->group(function () {
    Route::prefix('dashboard')->name('dashboard.')->controller(ManagerDashboardController::class)->group(function () {
        Route::get('/', 'index')->name('index');
    });
});

// Applicant Route
Route::prefix('applicant')->name('applicant.')->middleware(['auth', 'role:applicant'])->group(function () {
    Route::prefix('dashboard')->name('dashboard.')->controller(ApplicantDashboardController::class)->group(function () {
        Route::get('/', 'index')->name('index');
    });
});

// Superuser Route
Route::prefix('superuser')->name('superuser.')->middleware(['auth', 'role:superuser'])->group(function () {
    Route::prefix('dashboard')->name('dashboard.')->controller(SuperuserDashboardController::class)->group(function () {
        Route::get('/', 'index')->name('index');
    });

    Route::prefix('department')->name('department.')->controller(SuperuserDepartmentController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/data-table', 'datatable')->name('datatable');
        Route::post('/store', 'store')->name('store');
        Route::put('/update', 'update')->name('update');
        Route::delete('/delete', 'destroy')->name('destroy');
        Route::post('/restore', 'restore')->name('restore');
    });

    Route::prefix('user')->name('user.')->controller(SuperuserUserController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/data-table', 'datatable')->name('datatable');
        Route::post('/store', 'store')->name('store');
        Route::put('/update', 'update')->name('update');
        Route::delete('/delete', 'destroy')->name('destroy');
        Route::post('/restore', 'restore')->name('restore');
    });
});