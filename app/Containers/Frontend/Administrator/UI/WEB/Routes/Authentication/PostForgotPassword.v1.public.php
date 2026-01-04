<?php

use App\Containers\Frontend\Administrator\UI\WEB\Controllers\AuthenticationController;
use Illuminate\Support\Facades\Route;

Route::post('/recuperar-contrasena', [AuthenticationController::class, 'postForgotPassword'])
    ->prefix(config('app.admin_prefix'))
    ->name('auth.forgot_password_post')
    ->middleware(['guest'])
    ->domain(parse_url(config('app.admin_url'), PHP_URL_HOST));

