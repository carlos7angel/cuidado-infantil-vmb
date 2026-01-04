<?php

use App\Containers\Frontend\Administrator\UI\WEB\Controllers\AuthenticationController;
use Illuminate\Support\Facades\Route;

Route::get('/ingreso', [AuthenticationController::class, 'showLoginForm'])
    ->prefix(config('app.admin_prefix'))
    ->name('auth.login')
    ->middleware(['guest'])
    ->domain(parse_url(config('app.admin_url'), PHP_URL_HOST));

