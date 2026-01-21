<?php

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'main']);
Route::get('pages/main', [PageController::class, 'main'])->name('pages.main');
Route::get('pages/statistics', [PageController::class, 'statistics'])->name('pages.statistics');
Route::get('pages/forecast', [PageController::class, 'forecast'])->name('pages.forecast');
