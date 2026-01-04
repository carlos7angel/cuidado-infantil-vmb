<?php

use App\Containers\Frontend\Administrator\UI\WEB\Controllers\AuthenticationController;
use Illuminate\Support\Facades\Route;

Route::post('/restablecer-contrasena', [AuthenticationController::class, 'postResetPassword'])
    ->prefix(config('app.admin_prefix'))
    ->name('auth.reset_password_post')
    ->middleware(['guest'])
    ->domain(parse_url(config('app.admin_url'), PHP_URL_HOST));

