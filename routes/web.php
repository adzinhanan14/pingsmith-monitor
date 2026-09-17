<?php

use App\Http\Controllers\AlertChannelWebController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MonitorWebController;
use App\Http\Controllers\StatusPageController;
use App\Http\Controllers\WebAuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [WebAuthController::class, 'create'])->name('login');
    Route::post('/login', [WebAuthController::class, 'store'])->name('login.store');
});

// Public Status Page
Route::get('/status/{slug}', [StatusPageController::class, 'show'])->name('status.show');

Route::middleware('auth')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    Route::post('/monitors', [MonitorWebController::class, 'store'])->name('web.monitors.store');
    Route::post('/monitors/{monitor}/check', [MonitorWebController::class, 'check'])->name('web.monitors.check');
    Route::patch('/monitors/{monitor}/pause', [MonitorWebController::class, 'togglePause'])->name('web.monitors.pause');
    Route::delete('/monitors/{monitor}', [MonitorWebController::class, 'destroy'])->name('web.monitors.destroy');
    Route::post('/alerts', [AlertChannelWebController::class, 'store'])->name('web.alerts.store');
    Route::patch('/alerts/{channel}/toggle', [AlertChannelWebController::class, 'toggle'])->name('web.alerts.toggle');
    Route::delete('/alerts/{channel}', [AlertChannelWebController::class, 'destroy'])->name('web.alerts.destroy');
    Route::post('/logout', [WebAuthController::class, 'destroy'])->name('logout');
});
