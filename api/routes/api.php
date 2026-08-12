<?php

use App\Http\Controllers\Api\V1\ActionController;
use App\Http\Controllers\Api\V1\BlockController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\DeviceController;
use App\Http\Controllers\Api\V1\FaqController;
use App\Http\Controllers\Api\V1\MetricsController;
use App\Http\Controllers\Api\V1\NewsController;
use App\Http\Controllers\Api\V1\PageController;
use App\Http\Controllers\Api\V1\SiteController;
use App\Http\Controllers\Api\V1\TariffController;
use App\Http\Controllers\Api\V1\TenderController;
use App\Http\Controllers\Api\V1\VacancyController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.v1.')->group(function (): void {
    Route::get('site', SiteController::class)->name('site');
    Route::get('metrics', MetricsController::class)->name('metrics');
    Route::get('categories/{taxonomy}', CategoryController::class)->name('categories');
    Route::get('faqs', FaqController::class)->name('faqs');
    Route::get('blocks/{page}', BlockController::class)->name('blocks.show');
    Route::get('pages/{page}', PageController::class)->name('pages.show');

    Route::get('tariffs', [TariffController::class, 'index'])->name('tariffs.index');
    Route::get('tariffs/{tariff}', [TariffController::class, 'show'])->name('tariffs.show');
    Route::get('news', [NewsController::class, 'index'])->name('news.index');
    Route::get('news/{news}', [NewsController::class, 'show'])->name('news.show');
    Route::get('actions', [ActionController::class, 'index'])->name('actions.index');
    Route::get('actions/{action}', [ActionController::class, 'show'])->name('actions.show');
    Route::get('vacancies', [VacancyController::class, 'index'])->name('vacancies.index');
    Route::get('vacancies/{vacancy}', [VacancyController::class, 'show'])->name('vacancies.show');
    Route::get('tenders', [TenderController::class, 'index'])->name('tenders.index');
    Route::get('tenders/{tender}', [TenderController::class, 'show'])->name('tenders.show');
    Route::get('devices', [DeviceController::class, 'index'])->name('devices.index');
    Route::get('devices/{device}', [DeviceController::class, 'show'])->name('devices.show');
});
