<?php

use App\Containers\Frontend\Administrator\UI\WEB\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/usuarios/administradores/guardar', [UserController::class, 'store'])
    ->prefix(config('app.admin_prefix'))
    ->name('admin.user.store')
    ->middleware(['auth:web'])
    ->domain(parse_url(config('app.admin_url'), PHP_URL_HOST));
