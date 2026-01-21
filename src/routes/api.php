<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ForecastController;
use App\Http\Controllers\Api\StressTestController;
use App\Http\Controllers\Api\AiRecommendationController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\ActivityLogController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::apiResource('companies', CompanyController::class);
Route::apiResource('transactions', TransactionController::class);

Route::apiResource('users', UserController::class);
Route::apiResource('accounts', AccountController::class);

Route::apiResource('categories', CategoryController::class);
Route::apiResource('forecasts', ForecastController::class);

Route::apiResource('stress-tests', StressTestController::class);
Route::apiResource('ai-recommendations', AiRecommendationController::class);
Route::apiResource('reports', ReportController::class);
Route::apiResource('activity-logs', ActivityLogController::class);

