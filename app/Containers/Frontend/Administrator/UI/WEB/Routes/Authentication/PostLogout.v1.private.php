<?php

use App\Containers\Frontend\Administrator\UI\WEB\Controllers\AuthenticationController;
use Illuminate\Support\Facades\Route;

Route::post('/logout', [AuthenticationController::class, 'postLogout'])
    ->prefix(config('app.admin_prefix'))
    ->name('auth.logout_post')
    ->middleware(['auth:web'])
    ->domain(parse_url(config('app.admin_url'), PHP_URL_HOST));

