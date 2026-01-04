<?php

use App\Containers\Monitoring\Attendance\UI\WEB\Controllers\UpdateAttendanceController;
use Illuminate\Support\Facades\Route;

Route::patch('attendances/{id}', UpdateAttendanceController::class)
    ->middleware(['auth:web']);

