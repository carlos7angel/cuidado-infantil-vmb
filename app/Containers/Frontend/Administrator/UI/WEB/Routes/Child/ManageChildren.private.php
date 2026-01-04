<?php

use App\Containers\Frontend\Administrator\UI\WEB\Controllers\ChildController;
use Illuminate\Support\Facades\Route;

Route::get('/infantes', [ChildController::class, 'manage'])
    ->prefix(config('app.admin_prefix'))
    ->name('admin.child.manage')
    ->middleware(['auth:web'])
    ->domain(parse_url(config('app.admin_url'), PHP_URL_HOST));

