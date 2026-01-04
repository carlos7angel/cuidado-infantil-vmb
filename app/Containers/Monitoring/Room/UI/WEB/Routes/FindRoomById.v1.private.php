<?php

use App\Containers\Monitoring\Room\UI\WEB\Controllers\FindRoomByIdController;
use Illuminate\Support\Facades\Route;

Route::get('rooms/{id}', FindRoomByIdController::class)
    ->middleware(['auth:web']);

