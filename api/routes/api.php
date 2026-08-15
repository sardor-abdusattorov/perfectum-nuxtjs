<?php

use App\Http\Controllers\Api\V1\ActionController;
use App\Http\Controllers\Api\V1\ApplicationController;
use App\Http\Controllers\Api\V1\BlockController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\CdmaNumberController;
use App\Http\Controllers\Api\V1\CoverageController;
use App\Http\Controllers\Api\V1\DeviceController;
use App\Http\Controllers\Api\V1\DocumentController;
use App\Http\Controllers\Api\V1\FaqController;
use App\Http\Controllers\Api\V1\MetricsController;
use App\Http\Controllers\Api\V1\NewsController;
use App\Http\Controllers\Api\V1\NumberController;
use App\Http\Controllers\Api\V1\OfficeController;
use App\Http\Controllers\Api\V1\PageController;
use App\Http\Controllers\Api\V1\RedirectController;
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
    Route::get('documents', DocumentController::class)->name('documents');
    Route::get('coverage', [CoverageController::class, 'index'])->name('coverage');
    Route::get('coverage/{layer}', [CoverageController::class, 'show'])->name('coverage.show');
    Route::get('offices', OfficeController::class)->name('offices');
    Route::post('numbers', NumberController::class)->name('numbers');
    Route::get('cdma-numbers/filters', [CdmaNumberController::class, 'filters'])->name('cdma-numbers.filters');
    Route::post('cdma-numbers', [CdmaNumberController::class, 'search'])->name('cdma-numbers.search');
    Route::post('applications', ApplicationController::class)->middleware('throttle:10,1')->name('applications.store');
    Route::get('blocks/{page}', BlockController::class)->name('blocks.show');
    Route::get('pages/{page}', PageController::class)->name('pages.show');
    Route::get('redirects', RedirectController::class)->name('redirects');

    Route::get('tariffs', [TariffController::class, 'index'])->name('tariffs.index');
    Route::get('tariffs/files', [TariffController::class, 'files'])->name('tariffs.files');
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
