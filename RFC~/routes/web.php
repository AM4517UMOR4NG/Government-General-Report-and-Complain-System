<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Authentication Routes
Auth::routes();

// Logout confirmation page
Route::get('/logout/confirm', function () {
    return view('auth.logout');
})->name('logout.confirm');

// Additional logout route for easier access
Route::get('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout.get');

// Public Routes
Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard Routes based on user role
Route::middleware(['auth'])->group(function () {
    // Redirect based on user role
    Route::get('/home', function () {
        $user = auth()->user();
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isDepartmentHead() || $user->isStaff()) {
            return redirect()->route('administration.dashboard');
        } else {
            return redirect()->route('citizen.dashboard');
        }
    })->name('home');

    // Admin Dashboard Routes
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/reports', [App\Http\Controllers\AdminDashboardController::class, 'reports'])->name('reports');
        // Tambahan fitur admin
        Route::post('/reports/{id}/confirm', [App\Http\Controllers\AdminDashboardController::class, 'confirmReport'])->name('reports.confirm');
        Route::post('/reports/{id}/assign', [App\Http\Controllers\AdminDashboardController::class, 'assignReport'])->name('reports.assign');
        Route::get('/reports/{id}/edit', [App\Http\Controllers\AdminDashboardController::class, 'editReport'])->name('reports.edit');
        Route::put('/reports/{id}', [App\Http\Controllers\AdminDashboardController::class, 'updateReport'])->name('reports.update');
        Route::delete('/reports/{id}', [App\Http\Controllers\AdminDashboardController::class, 'deleteReport'])->name('reports.delete');
        Route::get('/complaints', [App\Http\Controllers\AdminDashboardController::class, 'complaints'])->name('complaints');
        // Tambahan fitur admin untuk keluhan
        Route::post('/complaints/{id}/confirm', [App\Http\Controllers\AdminDashboardController::class, 'confirmComplaint'])->name('complaints.confirm');
        Route::post('/complaints/{id}/assign', [App\Http\Controllers\AdminDashboardController::class, 'assignComplaint'])->name('complaints.assign');
        Route::get('/complaints/{id}/edit', [App\Http\Controllers\AdminDashboardController::class, 'editComplaint'])->name('complaints.edit');
        Route::put('/complaints/{id}', [App\Http\Controllers\AdminDashboardController::class, 'updateComplaint'])->name('complaints.update');
        Route::delete('/complaints/{id}', [App\Http\Controllers\AdminDashboardController::class, 'deleteComplaint'])->name('complaints.delete');
        Route::get('/users', [App\Http\Controllers\AdminDashboardController::class, 'users'])->name('users');
        Route::get('/departments', [App\Http\Controllers\AdminDashboardController::class, 'departments'])->name('departments');
    });

    // Administration Dashboard Routes (Department Head & Staff)
    Route::middleware(['department_head', 'staff'])->prefix('administration')->name('administration.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\AdministrationDashboardController::class, 'index'])->name('dashboard');
        Route::get('/reports', [App\Http\Controllers\AdministrationDashboardController::class, 'reports'])->name('reports');
        Route::get('/complaints', [App\Http\Controllers\AdministrationDashboardController::class, 'complaints'])->name('complaints');
        Route::get('/staff', [App\Http\Controllers\AdministrationDashboardController::class, 'staff'])->name('staff');
        Route::post('/reports/{id}/assign', [App\Http\Controllers\AdministrationDashboardController::class, 'assignReport'])->name('reports.assign');
        Route::post('/complaints/{id}/assign', [App\Http\Controllers\AdministrationDashboardController::class, 'assignComplaint'])->name('complaints.assign');
    });

    // Citizen Dashboard Routes
    Route::middleware(['citizen'])->prefix('citizen')->name('citizen.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\CitizenDashboardController::class, 'index'])->name('dashboard');
        Route::get('/reports', [App\Http\Controllers\CitizenDashboardController::class, 'myReports'])->name('reports.index');
        Route::get('/reports/create', [App\Http\Controllers\CitizenDashboardController::class, 'createReport'])->name('reports.create');
        Route::post('/reports', [App\Http\Controllers\CitizenDashboardController::class, 'storeReport'])->name('reports.store');
        Route::get('/reports/{id}', [App\Http\Controllers\CitizenDashboardController::class, 'showReport'])->name('reports.show');
        Route::get('/complaints', [App\Http\Controllers\CitizenDashboardController::class, 'myComplaints'])->name('complaints.index');
        Route::get('/complaints/create', [App\Http\Controllers\CitizenDashboardController::class, 'createComplaint'])->name('complaints.create');
        Route::post('/complaints', [App\Http\Controllers\CitizenDashboardController::class, 'storeComplaint'])->name('complaints.store');
        Route::get('/complaints/{id}', [App\Http\Controllers\CitizenDashboardController::class, 'showComplaint'])->name('complaints.show');
    });
});

// API Routes for AJAX requests
Route::middleware(['auth'])->group(function () {
    Route::get('/api/departments', [App\Http\Controllers\DepartmentController::class, 'apiIndex']);
    Route::get('/api/reports/stats', [App\Http\Controllers\ReportController::class, 'stats']);
    Route::get('/api/complaints/stats', [App\Http\Controllers\ComplaintController::class, 'stats']);
});
