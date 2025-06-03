<?php
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\VendorController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Stakeholder;

Route::post('/vendors/apply', [VendorController::class, 'apply']);
Route::get('/analytics/demand', [AnalyticsController::class, 'demandPrediction']);
Route::get('/analytics/segments', [AnalyticsController::class, 'customerSegments']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/stakeholders/{type}', function ($type) {
    return Stakeholder::select('id', 'name')
        ->where('type', $type)
        ->get();
});
