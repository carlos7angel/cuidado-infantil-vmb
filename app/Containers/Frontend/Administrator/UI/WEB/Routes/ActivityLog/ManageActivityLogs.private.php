<?php

use App\Containers\Frontend\Administrator\UI\WEB\Controllers\ActivityLogController;
use Illuminate\Support\Facades\Route;

Route::get('/logs-actividad', [ActivityLogController::class, 'manage'])
    ->prefix(config('app.admin_prefix'))
    ->name('admin.activity_log.manage')
    ->middleware(['auth:web'])
    ->domain(parse_url(config('app.admin_url'), PHP_URL_HOST));

