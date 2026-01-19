<?php

use App\Containers\Frontend\Administrator\UI\WEB\Controllers\ActivityLogController;
use Illuminate\Support\Facades\Route;

Route::post('/logs-actividad/json', [ActivityLogController::class, 'listJsonDataTable'])
    ->prefix(config('app.admin_prefix'))
    ->name('admin.activity_log.list_json_dt')
    ->middleware(['auth:web'])
    ->domain(parse_url(config('app.admin_url'), PHP_URL_HOST));

