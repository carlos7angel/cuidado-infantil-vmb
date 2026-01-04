<?php

use App\Containers\Frontend\Administrator\UI\WEB\Controllers\ChildcareCenterController;
use Illuminate\Support\Facades\Route;

Route::post('/centros/guardar', [ChildcareCenterController::class, 'store'])
    ->prefix(config('app.admin_prefix'))
    ->name('admin.childcare_center.store')
    ->middleware(['auth:web'])
    ->domain(parse_url(config('app.admin_url'), PHP_URL_HOST));

Route::post('/centros/guardar/{id}', [ChildcareCenterController::class, 'store'])
    ->prefix(config('app.admin_prefix'))
    ->name('admin.childcare_center.update')
    ->middleware(['auth:web'])
    ->domain(parse_url(config('app.admin_url'), PHP_URL_HOST));

