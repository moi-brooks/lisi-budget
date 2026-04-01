<?php

use App\Http\Controllers\Admin\BudgetController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\EmetteurController;
use App\Http\Controllers\Admin\EngagementController as AdminEngagementController;
use App\Http\Controllers\Admin\LigneBudgetaireController;
use App\Http\Controllers\Admin\PropositionController;
use App\Http\Controllers\Emetteur\DashboardController as EmetteurDashboardController;
use App\Http\Controllers\Emetteur\EngagementController as EmetteurEngagementController;
use App\Http\Controllers\Emetteur\LigneProposeeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('emetteur.dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        Route::resource('budgets', BudgetController::class);
        Route::resource('budgets.lignes', LigneBudgetaireController::class)->except(['show']);
        Route::resource('emetteurs', EmetteurController::class);
        
        Route::get('/propositions', [PropositionController::class, 'index'])->name('propositions.index');
        Route::post('/propositions/{id}/approve', [PropositionController::class, 'approve'])->name('propositions.approve');
        Route::post('/propositions/{id}/reject', [PropositionController::class, 'reject'])->name('propositions.reject');
        
        Route::get('/engagements', [AdminEngagementController::class, 'index'])->name('engagements.index');
        Route::get('/engagements/{id}', [AdminEngagementController::class, 'show'])->name('engagements.show');
        Route::post('/engagements/{id}/approve', [AdminEngagementController::class, 'approve'])->name('engagements.approve');
        Route::post('/engagements/{id}/reject', [AdminEngagementController::class, 'reject'])->name('engagements.reject');
        Route::post('/engagements/{id}/tva', [AdminEngagementController::class, 'setTva'])->name('engagements.tva');
        Route::get('/engagements/{id}/download', [AdminEngagementController::class, 'download'])->name('engagements.download');
    });

    // Emetteur Routes
    Route::middleware('role:emetteur')->prefix('emetteur')->name('emetteur.')->group(function () {
        Route::get('/dashboard', [EmetteurDashboardController::class, 'index'])->name('dashboard');
        
        Route::resource('lignes', LigneProposeeController::class)->except(['show']);
        
        Route::resource('engagements', EmetteurEngagementController::class)->only(['index', 'create', 'store', 'show']);
        Route::post('/engagements/{id}/besoins', [EmetteurEngagementController::class, 'storeBesoin'])->name('engagements.besoins.store');
        Route::get('/engagements/{id}/download', [EmetteurEngagementController::class, 'download'])->name('engagements.download');
        Route::patch('/besoins/{id}/livraison', [EmetteurEngagementController::class, 'markLivre'])->name('besoins.livraison');
    });
});

require __DIR__.'/auth.php';
