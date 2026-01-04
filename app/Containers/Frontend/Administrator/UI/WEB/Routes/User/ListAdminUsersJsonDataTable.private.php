<?php

use App\Containers\Frontend\Administrator\UI\WEB\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/usuarios/administradores/json', [UserController::class, 'listAdminUsersJsonDataTable'])
    ->prefix(config('app.admin_prefix'))
    ->name('admin.user.list_admin_json_dt')
    ->middleware(['auth:web'])
    ->domain(parse_url(config('app.admin_url'), PHP_URL_HOST));
