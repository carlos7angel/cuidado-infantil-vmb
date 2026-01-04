<?php

use App\Containers\Frontend\Administrator\UI\WEB\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

Route::post('/grupos-salas/json', [RoomController::class, 'listJsonDataTable'])
    ->prefix(config('app.admin_prefix'))
    ->name('admin.room.list_json_dt')
    ->middleware(['auth:web'])
    ->domain(parse_url(config('app.admin_url'), PHP_URL_HOST));

