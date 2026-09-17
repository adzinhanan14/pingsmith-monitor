<?php

use App\Http\Controllers\Api\AlertChannelController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MonitorController;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\UptimeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgot'])->middleware('throttle:5,1');
Route::post('/reset-password', [AuthController::class, 'reset']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/teams', [TeamController::class, 'index']);
    Route::post('/teams', [TeamController::class, 'store']);
    Route::post('/teams/{team}/invitations', [TeamController::class, 'invite']);
    Route::post('/invitations/{token}/accept', [TeamController::class, 'accept']);
    Route::get('/teams/{team}/alert-channels', [AlertChannelController::class, 'index']);
    Route::post('/teams/{team}/alert-channels', [AlertChannelController::class, 'store']);
    Route::patch('/teams/{team}/alert-channels/{channel}', [AlertChannelController::class, 'update']);
    Route::delete('/teams/{team}/alert-channels/{channel}', [AlertChannelController::class, 'destroy']);
    Route::get('/teams/{team}/uptime', [UptimeController::class, 'summary']);
    Route::get('/teams/{team}/incidents', [UptimeController::class, 'incidents']);
    Route::apiResource('/teams/{team}/monitors', MonitorController::class);
    Route::post('/teams/{team}/monitors/{monitor}/test', [MonitorController::class, 'test']);
    Route::get('/teams/{team}/monitors/{monitor}/logs', [MonitorController::class, 'logs']);
    Route::get('/teams/{team}/monitors/{monitor}/incidents', [MonitorController::class, 'incidents']);
});
