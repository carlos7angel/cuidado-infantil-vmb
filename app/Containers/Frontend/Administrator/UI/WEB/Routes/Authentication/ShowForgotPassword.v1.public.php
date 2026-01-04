<?php

use App\Containers\Frontend\Administrator\UI\WEB\Controllers\AuthenticationController;
use Illuminate\Support\Facades\Route;

Route::get('/recuperar-contrasena', [AuthenticationController::class, 'showForgotPasswordForm'])
    ->prefix(config('app.admin_prefix'))
    ->name('auth.forgot_password')
    ->middleware(['guest'])
    ->domain(parse_url(config('app.admin_url'), PHP_URL_HOST));

