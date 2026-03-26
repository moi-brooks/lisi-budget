<?php

use App\Http\Controllers\BesoinController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmetteurController;
use App\Http\Controllers\EngagementController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Besoins — all authenticated users can see/create
    Route::resource('besoins', BesoinController::class)->except(['show']);

    // Engagements — all authenticated users can see/create
    Route::resource('engagements', EngagementController::class)->only(['index', 'create', 'store', 'show', 'update']);

    // Budgets & Emetteurs — admin and gestionnaire only
    Route::middleware(['role:admin,gestionnaire'])->group(function () {
        Route::resource('budgets', BudgetController::class)->except(['destroy']);
        Route::resource('emetteurs', EmetteurController::class)->except(['destroy']);
    });
});

require __DIR__.'/auth.php';
