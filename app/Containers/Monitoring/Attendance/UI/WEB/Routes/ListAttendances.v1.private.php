<?php

use App\Containers\Monitoring\Attendance\UI\WEB\Controllers\ListAttendancesController;
use Illuminate\Support\Facades\Route;

Route::get('attendances', ListAttendancesController::class)
    ->middleware(['auth:web']);

