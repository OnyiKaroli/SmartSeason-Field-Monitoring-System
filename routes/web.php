<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AgentDashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Routes are organized by role. The root '/' redirects authenticated users
| to their dashboard. Public routes are minimal — registration is not open
| to the public; the admin creates user accounts through the admin panel.
|
*/

// Root: redirect to login if not authenticated, or to the right dashboard
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('agent.dashboard');
    }
    return redirect()->route('login');
});

// -------------------------------------------------------------------------
// Admin routes — require auth + admin role
// -------------------------------------------------------------------------
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');
    });

// -------------------------------------------------------------------------
// Agent routes — require auth (any authenticated user may attempt /agent,
// but data is scoped to the authenticated user's assigned fields)
// -------------------------------------------------------------------------
Route::middleware(['auth'])
    ->prefix('agent')
    ->name('agent.')
    ->group(function () {
        Route::get('/dashboard', [AgentDashboardController::class, 'index'])
            ->name('dashboard');
    });

// -------------------------------------------------------------------------
// Profile (Breeze default — kept for completeness)
// -------------------------------------------------------------------------
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
