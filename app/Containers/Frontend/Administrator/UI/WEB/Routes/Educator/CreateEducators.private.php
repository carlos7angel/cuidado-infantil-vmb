<?php

use App\Containers\Frontend\Administrator\UI\WEB\Controllers\EducatorController;
use Illuminate\Support\Facades\Route;

Route::get('/educadores/nuevo', [EducatorController::class, 'form_create'])
    ->prefix(config('app.admin_prefix'))
    ->name('admin.educator.form_create')
    ->middleware(['auth:web'])
    ->domain(parse_url(config('app.admin_url'), PHP_URL_HOST));

