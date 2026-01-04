<?php

use App\Containers\Frontend\Administrator\UI\WEB\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/usuarios/administradores/{user_id}/edit', [UserController::class, 'edit'])
    ->prefix(config('app.admin_prefix'))
    ->name('admin.user.edit')
    ->middleware(['auth:web'])
    ->domain(parse_url(config('app.admin_url'), PHP_URL_HOST));
