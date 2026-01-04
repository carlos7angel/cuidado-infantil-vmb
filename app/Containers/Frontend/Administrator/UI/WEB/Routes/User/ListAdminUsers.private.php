<?php

use App\Containers\Frontend\Administrator\UI\WEB\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/usuarios/administradores', [UserController::class, 'listAdminUsers'])
    ->prefix(config('app.admin_prefix'))
    ->name('admin.user.list_admin')
    ->middleware(['auth:web'])
    ->domain(parse_url(config('app.admin_url'), PHP_URL_HOST));
