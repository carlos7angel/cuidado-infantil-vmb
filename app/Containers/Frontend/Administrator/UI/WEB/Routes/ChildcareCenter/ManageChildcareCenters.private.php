<?php

use App\Containers\Frontend\Administrator\UI\WEB\Controllers\ChildcareCenterController;
use Illuminate\Support\Facades\Route;

Route::post('/centros/json', [ChildcareCenterController::class, 'listJsonDataTable'])
    ->prefix(config('app.admin_prefix'))
    ->name('admin.childcare_center.list_json_dt')
    ->middleware(['auth:web'])
    ->domain(parse_url(config('app.admin_url'), PHP_URL_HOST));

