<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CctvLogController;
use App\Http\Controllers\InventoryCheckController;
use App\Http\Controllers\MaintenanceTicketController;
use App\Http\Controllers\ProfileController;

// Public Routes
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    
    // Dashboard - Accessible by all roles
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // ADMIN ROUTES
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::get('/reports/validation', [DashboardController::class, 'validation'])->name('reports.validation');
        Route::get('/reports/export', [DashboardController::class, 'exportPdf'])->name('reports.export');
    });

    // SECURITY ROUTES
    Route::middleware(['role:security'])->group(function () {
        // CCTV Logs
        Route::resource('cctv-logs', CctvLogController::class)->except(['show']);
        
        // Inventory Checks
        Route::get('/inventory/pos', [InventoryCheckController::class, 'createPos'])->name('inventory.pos.create');
        Route::post('/inventory/pos', [InventoryCheckController::class, 'storePos'])->name('inventory.pos.store');
        
        Route::get('/inventory/general', [InventoryCheckController::class, 'createGeneral'])->name('inventory.general.create');
        Route::post('/inventory/general', [InventoryCheckController::class, 'storeGeneral'])->name('inventory.general.store');
    });

    // MAINTENANCE ROUTES
    Route::middleware(['role:maintenance'])->group(function () {
        Route::get('/tickets', [MaintenanceTicketController::class, 'index'])->name('tickets.index');
        Route::get('/tickets/{ticket}', [MaintenanceTicketController::class, 'show'])->name('tickets.show');
        Route::put('/tickets/{ticket}', [MaintenanceTicketController::class, 'update'])->name('tickets.update');
    });

    // ADMIN & SECURITY - View tickets
    Route::middleware(['role:admin,security'])->group(function () {
        Route::get('/my-tickets', [MaintenanceTicketController::class, 'myTickets'])->name('my-tickets');
    });
});