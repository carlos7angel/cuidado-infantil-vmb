<?php

use App\Containers\Frontend\Administrator\UI\WEB\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

Route::post('/grupos-salas/guardar', [RoomController::class, 'store'])
    ->prefix(config('app.admin_prefix'))
    ->name('admin.room.store')
    ->middleware(['auth:web'])
    ->domain(parse_url(config('app.admin_url'), PHP_URL_HOST));

Route::post('/grupos-salas/guardar/{id}', [RoomController::class, 'store'])
    ->prefix(config('app.admin_prefix'))
    ->name('admin.room.update')
    ->middleware(['auth:web'])
    ->domain(parse_url(config('app.admin_url'), PHP_URL_HOST));

