<?php

use App\Http\Controllers\Admin\BudgetController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\EmetteurController;
use App\Http\Controllers\Admin\EngagementController as AdminEngagementController;
use App\Http\Controllers\Admin\LigneBudgetaireController;
use App\Http\Controllers\Admin\PropositionController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Emetteur\DashboardController as EmetteurDashboardController;
use App\Http\Controllers\Emetteur\EngagementController as EmetteurEngagementController;
use App\Http\Controllers\Emetteur\LigneProposeeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Vite;

Route::get('/', function () {
    return redirect()->route('login');
});

// -----------------------------------------------------------------------------
// TEMPORAIRE — diagnostic déploiement Railway (erreur 500). À SUPPRIMER une fois
// le déploiement stabilisé : cette route est publique et n'a aucune protection.
// -----------------------------------------------------------------------------
Route::get('/debug-railway', function () {
    try {
        DB::connection()->getPdo();
        $dbOk = 'DB OK - ' . DB::connection()->getDatabaseName();
    } catch (\Exception $e) {
        $dbOk = 'DB FAIL: ' . $e->getMessage();
    }

    try {
        $viteAsset = Vite::asset('resources/css/app.css');
    } catch (\Throwable $e) {
        $viteAsset = 'VITE FAIL: ' . $e->getMessage();
    }

    return response()->json([
        'app_env' => env('APP_ENV'),
        'app_key_set' => !empty(env('APP_KEY')),
        'db_status' => $dbOk,
        'php_version' => PHP_VERSION,
        'app_url' => config('app.url'),
        'asset_url' => config('app.asset_url'),
        'vite_asset' => $viteAsset,
    ]);
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
        Route::get('/audit-log', [AuditLogController::class, 'index'])->name('audit-log');
        
        Route::resource('budgets', BudgetController::class);
        Route::resource('budgets.lignes', LigneBudgetaireController::class)->except(['show']);
        Route::resource('emetteurs', EmetteurController::class);
        Route::post('/emetteurs/{emetteur}/reset-password', [EmetteurController::class, 'resetPassword'])->name('emetteurs.reset-password');
        
        Route::get('/propositions', [PropositionController::class, 'index'])->name('propositions.index');
        Route::get('/propositions/export/excel', [PropositionController::class, 'exportExcel'])->name('propositions.export.excel');
        Route::get('/propositions/export/pdf', [PropositionController::class, 'exportPdf'])->name('propositions.export.pdf');
        Route::post('/propositions/{id}/approve', [PropositionController::class, 'approve'])->name('propositions.approve');
        Route::post('/propositions/{id}/reject', [PropositionController::class, 'reject'])->name('propositions.reject');
        
        Route::get('/engagements', [AdminEngagementController::class, 'index'])->name('engagements.index');
        Route::get('/engagements/export/excel', [AdminEngagementController::class, 'exportExcel'])->name('engagements.export.excel');
        Route::get('/engagements/export/pdf', [AdminEngagementController::class, 'exportPdf'])->name('engagements.export.pdf');
        Route::get('/engagements/{id}', [AdminEngagementController::class, 'show'])->name('engagements.show');
        Route::post('/engagements/{id}/approve', [AdminEngagementController::class, 'approve'])->name('engagements.approve');
        Route::post('/engagements/{id}/reject', [AdminEngagementController::class, 'reject'])->name('engagements.reject');
        Route::post('/engagements/{id}/tva', [AdminEngagementController::class, 'setTva'])->name('engagements.tva');
        Route::get('/engagements/{id}/download', [AdminEngagementController::class, 'download'])->name('engagements.download');

        // Aggregated Official Exports
        Route::get('/exports/besoins/{budget}', [ExportController::class, 'exportDocx'])->name('exports.besoins');
        Route::get('/exports/excel/{budget}', [ExportController::class, 'exportExcel'])->name('exports.excel');
    });

    // Emetteur Routes
    Route::middleware('role:emetteur')->prefix('emetteur')->name('emetteur.')->group(function () {
        Route::get('/dashboard', [EmetteurDashboardController::class, 'index'])->name('dashboard');
        Route::post('/change-password', [EmetteurDashboardController::class, 'changePassword'])->name('change-password');
        
        Route::resource('lignes', LigneProposeeController::class)->except(['show']);
        
        Route::resource('engagements', EmetteurEngagementController::class)->only(['index', 'create', 'store', 'show']);
        Route::post('/engagements/{id}/besoins', [EmetteurEngagementController::class, 'storeBesoin'])->name('engagements.besoins.store');
        Route::get('/engagements/{id}/download', [EmetteurEngagementController::class, 'download'])->name('engagements.download');
        Route::patch('/besoins/{id}/livraison', [EmetteurEngagementController::class, 'markLivre'])->name('besoins.livraison');
        Route::get('/export/eb', [EmetteurEngagementController::class, 'exportEb'])->name('export.eb');
    });
});

require __DIR__.'/auth.php';
