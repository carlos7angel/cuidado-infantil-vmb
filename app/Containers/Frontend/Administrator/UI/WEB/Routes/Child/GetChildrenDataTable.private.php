<?php

use App\Containers\Frontend\Administrator\UI\WEB\Controllers\ChildController;
use Illuminate\Support\Facades\Route;

Route::post('/infantes/json', [ChildController::class, 'listJsonDataTable'])
    ->prefix(config('app.admin_prefix'))
    ->name('admin.child.list_json_dt')
    ->middleware(['auth:web'])
    ->domain(parse_url(config('app.admin_url'), PHP_URL_HOST));

