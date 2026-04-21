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
| to their dashboard. Public routes are minimal - registration is not open
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
// Admin routes - require auth + admin role
// -------------------------------------------------------------------------
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');
    });

// -------------------------------------------------------------------------
// Field Management & View routes
// -------------------------------------------------------------------------
Route::middleware(['auth'])->group(function () {
    // Only admins can create, edit, or delete fields
    Route::middleware(['admin'])->group(function () {
        Route::resource('fields', \App\Http\Controllers\FieldController::class)->except(['index', 'show']);
    });

    // Both admins and agents can view fields
    Route::resource('fields', \App\Http\Controllers\FieldController::class)->only(['index', 'show']);

    // Field updates
    Route::post('/fields/{field}/updates', [\App\Http\Controllers\FieldUpdateController::class, 'store'])
        ->name('fields.updates.store');
});

// -------------------------------------------------------------------------
// Agent routes - require auth (any authenticated user may attempt /agent,
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
// Profile (Breeze default - kept for completeness)
// -------------------------------------------------------------------------
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Emergency Artisan Access (Render Free Tier Workaround)
Route::get('/admin/artisan/{command}', [\App\Http\Controllers\ArtisanController::class, 'run'])
    ->where('command', '.*');

