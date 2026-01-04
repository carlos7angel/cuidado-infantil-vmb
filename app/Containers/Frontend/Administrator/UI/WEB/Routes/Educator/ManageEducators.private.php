<?php

use App\Containers\Frontend\Administrator\UI\WEB\Controllers\EducatorController;
use Illuminate\Support\Facades\Route;

Route::get('/educadores', [EducatorController::class, 'manage'])
    ->prefix(config('app.admin_prefix'))
    ->name('admin.educator.manage')
    ->middleware(['auth:web'])
    ->domain(parse_url(config('app.admin_url'), PHP_URL_HOST));

