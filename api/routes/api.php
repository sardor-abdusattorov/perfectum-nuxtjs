<?php

use App\Http\Controllers\Api\V1\BlockController;
use App\Http\Controllers\Api\V1\MenuController;
use App\Http\Controllers\Api\V1\MetricsController;
use App\Http\Controllers\Api\V1\PageController;
use App\Http\Controllers\Api\V1\SettingsController;
use App\Http\Controllers\Api\V1\TranslationController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.v1.')->group(function (): void {
    Route::get('settings', SettingsController::class)->name('settings');
    Route::get('metrics', MetricsController::class)->name('metrics');
    Route::get('menus', MenuController::class)->name('menus');
    Route::get('translations', TranslationController::class)->name('translations');
    Route::get('blocks/{page}', BlockController::class)->name('blocks.show');
    Route::get('pages/{slug}', PageController::class)->name('pages.show');
});
