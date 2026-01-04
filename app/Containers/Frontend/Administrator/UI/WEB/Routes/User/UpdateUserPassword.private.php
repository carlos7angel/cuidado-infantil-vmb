<?php

use App\Containers\Frontend\Administrator\UI\WEB\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::put('/usuarios/administradores/{user_id}/password', [UserController::class, 'updatePassword'])
    ->prefix(config('app.admin_prefix'))
    ->name('admin.user.update_password')
    ->middleware(['auth:web'])
    ->domain(parse_url(config('app.admin_url'), PHP_URL_HOST));
