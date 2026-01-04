<?php

use App\Containers\Frontend\Administrator\UI\WEB\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

Route::get('/grupos-salas/editar/{id}', [RoomController::class, 'form_edit'])
    ->prefix(config('app.admin_prefix'))
    ->name('admin.room.form_edit')
    ->middleware(['auth:web'])
    ->domain(parse_url(config('app.admin_url'), PHP_URL_HOST));

