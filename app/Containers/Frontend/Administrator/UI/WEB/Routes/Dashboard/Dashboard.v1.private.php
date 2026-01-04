<?php

use App\Containers\Frontend\Administrator\UI\WEB\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [DashboardController::class, 'showIndexPage'])
    ->prefix(config('app.admin_prefix'))
    ->name('admin.dashboard')
    ->middleware(['auth:web'])
    ->domain(parse_url(config('app.admin_url'), PHP_URL_HOST));

