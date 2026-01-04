<?php

use App\Containers\Frontend\Administrator\UI\WEB\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/usuarios/administradores/{user_id}', [UserController::class, 'show'])
    ->prefix(config('app.admin_prefix'))
    ->name('admin.user.show')
    ->middleware(['auth:web'])
    ->domain(parse_url(config('app.admin_url'), PHP_URL_HOST));
