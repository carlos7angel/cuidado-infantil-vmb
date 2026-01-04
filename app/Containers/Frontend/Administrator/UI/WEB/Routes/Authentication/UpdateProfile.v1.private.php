<?php

use App\Containers\Frontend\Administrator\UI\WEB\Controllers\AuthenticationController;
use Illuminate\Support\Facades\Route;

Route::get('/perfil', [AuthenticationController::class, 'formProfile'])
    ->prefix(config('app.admin_prefix'))
    ->name('auth.form_profile')
    ->middleware(['auth:web'])
    ->domain(parse_url(config('app.admin_url'), PHP_URL_HOST));

Route::post('/perfil/actualizar-contrasena', [AuthenticationController::class, 'updatePasswordProfile'])
    ->prefix(config('app.admin_prefix'))
    ->name('auth.update_password_profile')
    ->middleware(['auth:web'])
    ->domain(parse_url(config('app.admin_url'), PHP_URL_HOST));

Route::post('/perfil/actualizar-nombre', [AuthenticationController::class, 'updateUsernameProfile'])
    ->prefix(config('app.admin_prefix'))
    ->name('auth.update_username_profile')
    ->middleware(['auth:web'])
    ->domain(parse_url(config('app.admin_url'), PHP_URL_HOST));

