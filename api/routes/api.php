<?php

use App\Http\Controllers\Api\V1\ActionController;
use App\Http\Controllers\Api\V1\BlockController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\DeviceController;
use App\Http\Controllers\Api\V1\FaqController;
use App\Http\Controllers\Api\V1\MenuController;
use App\Http\Controllers\Api\V1\MetricsController;
use App\Http\Controllers\Api\V1\NewsController;
use App\Http\Controllers\Api\V1\PageController;
use App\Http\Controllers\Api\V1\SettingsController;
use App\Http\Controllers\Api\V1\SocialController;
use App\Http\Controllers\Api\V1\TenderController;
use App\Http\Controllers\Api\V1\TranslationController;
use App\Http\Controllers\Api\V1\VacancyController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.v1.')->group(function (): void {
    Route::get('settings', SettingsController::class)->name('settings');
    Route::get('metrics', MetricsController::class)->name('metrics');
    Route::get('menus', MenuController::class)->name('menus');
    Route::get('socials', SocialController::class)->name('socials');
    Route::get('translations', TranslationController::class)->name('translations');
    Route::get('categories/{type}', CategoryController::class)->name('categories');
    Route::get('faqs', FaqController::class)->name('faqs');
    Route::get('blocks/{page}', BlockController::class)->name('blocks.show');
    Route::get('pages/{slug}', PageController::class)->name('pages.show');

    Route::get('news', [NewsController::class, 'index'])->name('news.index');
    Route::get('news/{slug}', [NewsController::class, 'show'])->name('news.show');
    Route::get('actions', [ActionController::class, 'index'])->name('actions.index');
    Route::get('actions/{slug}', [ActionController::class, 'show'])->name('actions.show');
    Route::get('vacancies', [VacancyController::class, 'index'])->name('vacancies.index');
    Route::get('vacancies/{slug}', [VacancyController::class, 'show'])->name('vacancies.show');
    Route::get('tenders', [TenderController::class, 'index'])->name('tenders.index');
    Route::get('tenders/{slug}', [TenderController::class, 'show'])->name('tenders.show');
    Route::get('devices', [DeviceController::class, 'index'])->name('devices.index');
    Route::get('devices/{slug}', [DeviceController::class, 'show'])->name('devices.show');
});
