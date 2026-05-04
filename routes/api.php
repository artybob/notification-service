<?php

use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ReportController;
use Illuminate\Support\Facades\Route;

Route::prefix('notifications')->group(function () {
    Route::post('/', [NotificationController::class, 'store']);
    Route::get('/{notification}', [NotificationController::class, 'show']);
    Route::get('/user/{userId}/history', [NotificationController::class, 'userHistory']);
});

Route::prefix('reports')->group(function () {
    Route::post('/generate', [ReportController::class, 'generate']);
    Route::get('/{reportId}/status', [ReportController::class, 'status']);
    Route::get('/{reportId}/download', [ReportController::class, 'download'])->name('reports.download');
});
