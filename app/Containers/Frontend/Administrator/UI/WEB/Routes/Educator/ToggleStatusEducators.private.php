<?php

use App\Containers\Frontend\Administrator\UI\WEB\Controllers\EducatorController;
use Illuminate\Support\Facades\Route;

Route::post('/educadores/{id}/toggle-estado', [EducatorController::class, 'toggleStatus'])
    ->prefix(config('app.admin_prefix'))
    ->name('admin.educator.toggle_status')
    ->middleware(['auth:web'])
    ->domain(parse_url(config('app.admin_url'), PHP_URL_HOST));

