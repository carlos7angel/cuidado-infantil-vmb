<?php

use App\Containers\Frontend\Administrator\UI\WEB\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

Route::get('/grupos-salas/nuevo', [RoomController::class, 'form_create'])
    ->prefix(config('app.admin_prefix'))
    ->name('admin.room.form_create')
    ->middleware(['auth:web'])
    ->domain(parse_url(config('app.admin_url'), PHP_URL_HOST));

