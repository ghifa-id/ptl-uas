<?php

use App\Http\Controllers\Administrator\DepartmentController as AdministratorDepartmentController;
use App\Http\Controllers\Administrator\UserController as AdministratorUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Administrator\DashboardController as AdministratorDashboardController;
use App\Http\Controllers\Administrator\VehicleController as AdministratorVehicleController;
use App\Http\Controllers\Applicant\ApplicantController;
use App\Http\Controllers\Manager\DashboardController as ManagerDashboardController;
use App\Http\Controllers\Applicant\DashboardController as ApplicantDashboardController;
use App\Http\Controllers\Superuser\DashboardController as SuperuserDashboardController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Manager\BookingController;
use App\Http\Controllers\Superuser\DepartmentController as SuperuserDepartmentController;
use App\Http\Controllers\Superuser\UserController as SuperuserUserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $roles = config('roles');
    if (Auth::check()) {
        if (Auth::user()->role === 'staff') {
            return redirect()->route('applicant.booking.index');
        } elseif (Auth::user()->role === 'superuser') {
            return redirect()->route('superuser.user.index');
        } else {
            return redirect()->route($roles[Auth::user()->role] . '.dashboard.index');
        }
    } else {
        return view('auth.login');
    }
})->name('login');

Route::post('/authenticate', [LoginController::class, 'login'])->name('authenticate');
Route::post('/logout', [LoginController::class, 'logout'])->middleware(['auth'])->name('logout');

Route::prefix('account')->name('account.')->controller(PasswordController::class)->group(function () {
    Route::get('/first-change', 'firstChange')->name('password.first-change');
    Route::post('/update-password', 'updatePassword')->name('password.update');
    Route::get('/skip', 'skipChange')->name('password.skipchange');
    Route::get('/profile', 'profile')->name('profile');
    Route::post('/update', 'updateProfile')->name('profile.update');
    Route::post('/change', 'changePassword')->name('password.change');
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
        Route::get('/data-table', 'datatable')->name('datatable');
        Route::get('/data-waiting-decision', 'fetchDataWaitingDecision')->name('fetchdecision');
        Route::post('/approved', 'approved')->name('approved');
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

    Route::prefix('vehicle')->name('vehicle.')->controller(AdministratorVehicleController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/data-table', 'datatable')->name('datatable');
        Route::post('/store', 'store')->name('store');
        Route::put('/update', 'update')->name('update');
        Route::delete('/delete', 'destroy')->name('destroy');

        Route::get('/type', 'typeVehicle')->name('type');
        Route::get('/type/data-table', 'datatableTypeVehicle')->name('type.datatable');
        Route::post('/type/store', 'storeTypeVehicle')->name('type.store');
        Route::put('/type/update', 'updateTypeVehicle')->name('type.update');
        Route::delete('/type/delete', 'destroyTypeVehicle')->name('type.destroy');
    });
});

// Manager Route
Route::prefix('manager')->name('manager.')->middleware(['auth', 'role:manager'])->group(function () {
    Route::prefix('dashboard')->name('dashboard.')->controller(ManagerDashboardController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/data-table', 'datatable')->name('datatable');
        Route::get('/data-waiting-decision', 'fetchDataWaitingDecision')->name('fetchdecision');
    });

    Route::prefix('booking')->name('booking.')->controller(BookingController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/data-table', 'datatable')->name('datatable');
        Route::post('/approved', 'approved')->name('approved');
        Route::post('/refused', 'refused')->name('refused');
    });
});

// Applicant Route
Route::prefix('applicant')->name('applicant.')->middleware(['auth', 'role:applicant'])->group(function () {
    Route::prefix('booking')->name('booking.')->controller(ApplicantController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/data-table', 'datatable')->name('datatable');
        Route::post('/store', 'store')->name('store');
        Route::post('/cancel', 'cancel')->name('cancel');
    });

    Route::prefix('return')->name('return.')->controller(ApplicantController::class)->group(function () {
        Route::get('/', 'return')->name('index');
        Route::get('/data-table', 'datatableReturn')->name('datatable');
        Route::get('/data-need-report', 'fetchDataVehicleNeedReport')->name('needreport');
        Route::post('/store', 'storeReport')->name('store');
        Route::post('/cancel', 'cancel')->name('cancel');
    });
});

// Superuser Route
Route::prefix('superuser')->name('superuser.')->middleware(['auth', 'role:superuser'])->group(function () {
    Route::prefix('dashboard')->name('dashboard.')->controller(SuperuserUserController::class)->group(function () {
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