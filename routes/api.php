<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\SyncController;

// Routes de synchronisation
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/sync/push', [SyncController::class, 'push']);
    Route::get('/sync/pull', [SyncController::class, 'pull']);
    Route::post('/sync/conflict', [SyncController::class, 'resolveConflict']);
    Route::get('/sync/status', [SyncController::class, 'status']);
});
