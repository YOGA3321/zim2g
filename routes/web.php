<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GoogleAuthController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Google User Auth
Route::get('/auth/google', [\App\Http\Controllers\Auth\GoogleLoginController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [\App\Http\Controllers\Auth\GoogleLoginController::class, 'handleGoogleCallback']);


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Google Drive Auth
    Route::get('/google/connect', [GoogleAuthController::class, 'redirectToGoogle'])->name('google.connect');
    Route::get('/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);
    Route::post('/google/disconnect', [GoogleAuthController::class, 'disconnect'])->name('google.disconnect');

    Route::get('/archives', [ArchiveController::class, 'index'])->name('archives.index');
    Route::get('/archives/my', [ArchiveController::class, 'myArchives'])->name('archives.my');
    Route::get('/archives/create', [ArchiveController::class, 'create'])->name('archives.create');
    Route::get('/archives/{id}', [ArchiveController::class, 'show'])->name('archives.show');
    Route::post('/archives', [ArchiveController::class, 'store'])->name('archives.store');
    Route::post('/archives/{id}/update-file', [ArchiveController::class, 'updateFile'])->name('archives.update-file');
    Route::delete('/archives/{archive}', [ArchiveController::class, 'destroy'])->name('archives.destroy');

    Route::resource('users', UserController::class);

    Route::get('/master-data', [MasterDataController::class, 'index'])->name('master-data.index');
    Route::post('/master-data/areas', [MasterDataController::class, 'storeArea'])->name('master-data.areas.store');
    Route::get('/master-data/areas/{area}/edit', [MasterDataController::class, 'editArea'])->name('master-data.areas.edit');
    Route::put('/master-data/areas/{area}', [MasterDataController::class, 'updateArea'])->name('master-data.areas.update');
    Route::delete('/master-data/areas/{area}', [MasterDataController::class, 'destroyArea'])->name('master-data.areas.destroy');
    
    Route::get('/master-data/{area}/components', [MasterDataController::class, 'components'])->name('master-data.components');
    Route::post('/master-data/components', [MasterDataController::class, 'storeComponent'])->name('master-data.components.store');
    Route::get('/master-data/components/{ziComponent}/edit', [MasterDataController::class, 'editComponent'])->name('master-data.components.edit');
    Route::put('/master-data/components/{ziComponent}', [MasterDataController::class, 'updateComponent'])->name('master-data.components.update');
    Route::delete('/master-data/components/{ziComponent}', [MasterDataController::class, 'destroyComponent'])->name('master-data.components.destroy');

    Route::get('/api/components-by-area/{area}', function ($areaId) {
        return \App\Models\ZiComponent::where('zi_area_id', $areaId)->get();
    });



    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
