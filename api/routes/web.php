<?php

declare(strict_types=1);

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Route;

/**
 * The backend serves the panel and nothing else, so its root sends the visitor
 * there. The address is asked of the panel rather than written out: it has
 * moved once already, and a hard-coded copy would have kept pointing at a page
 * that no longer exists.
 */
Route::get('/', fn () => redirect()->to(Filament::getPanel('admin')->getPath()));
