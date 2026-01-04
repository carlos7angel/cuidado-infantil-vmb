<?php

use App\Containers\Frontend\Administrator\UI\WEB\Controllers\EducatorController;
use Illuminate\Support\Facades\Route;

Route::post('/educadores/json', [EducatorController::class, 'listJsonDataTable'])
    ->prefix(config('app.admin_prefix'))
    ->name('admin.educator.list_json_dt')
    ->middleware(['auth:web'])
    ->domain(parse_url(config('app.admin_url'), PHP_URL_HOST));

