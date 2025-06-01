<?php
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\VendorController;
use Illuminate\Support\Facades\Route;

Route::post('/vendors/apply', [VendorController::class, 'apply']);
Route::get('/analytics/demand', [AnalyticsController::class, 'demandPrediction']);
Route::get('/analytics/segments', [AnalyticsController::class, 'customerSegments']);
