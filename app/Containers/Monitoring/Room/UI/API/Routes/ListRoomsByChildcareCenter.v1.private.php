<?php

use App\Containers\Monitoring\Room\UI\API\Controllers\ListRoomsByChildcareCenterController;
use Illuminate\Support\Facades\Route;

Route::get('rooms/by-childcare-center/{childcare_center_id}', ListRoomsByChildcareCenterController::class)
    ->middleware(['auth:api']);