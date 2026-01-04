<?php

use App\Containers\Monitoring\Room\UI\WEB\Controllers\ListRoomsController;
use Illuminate\Support\Facades\Route;

Route::get('rooms', ListRoomsController::class)
    ->middleware(['auth:web']);

