<?php

use App\Http\Controllers\Web\PageController;
use App\Http\Controllers\Api\ForecastController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'main']);
Route::get('pages/main', [PageController::class, 'main'])->name('pages.main');
Route::get('pages/statistics', [PageController::class, 'statistics'])->name('pages.statistics');
Route::get('pages/forecast', [PageController::class, 'forecast'])->name('pages.forecast');
Route::get('pages/login', [PageController::class, 'login'])->name('pages.login');

Route::get('pages/chartData', [PageController::class, 'chartData'])->name('pages.chartData');

// AI Forecast Routes
Route::prefix('api/ai')->group(function () {
    Route::post('/forecast/generate', [ForecastController::class, 'generateForecast']);
    Route::get('/forecast/{company_id}', [ForecastController::class, 'getForecasts']);
    Route::delete('/forecast/{company_id}', [ForecastController::class, 'deleteForecasts']);
});
