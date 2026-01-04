<?php

use App\Containers\Frontend\Administrator\UI\WEB\Controllers\EducatorController;
use Illuminate\Support\Facades\Route;

Route::post('/educadores/guardar', [EducatorController::class, 'store'])
    ->prefix(config('app.admin_prefix'))
    ->name('admin.educator.store')
    ->middleware(['auth:web'])
    ->domain(parse_url(config('app.admin_url'), PHP_URL_HOST));

Route::post('/educadores/guardar/{id}', [EducatorController::class, 'store'])
    ->prefix(config('app.admin_prefix'))
    ->name('admin.educator.update')
    ->middleware(['auth:web'])
    ->domain(parse_url(config('app.admin_url'), PHP_URL_HOST));

