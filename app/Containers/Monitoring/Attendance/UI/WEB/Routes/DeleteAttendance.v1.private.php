<?php

use App\Containers\Monitoring\Attendance\UI\WEB\Controllers\DeleteAttendanceController;
use Illuminate\Support\Facades\Route;

Route::delete('attendances/{id}', DeleteAttendanceController::class)
    ->middleware(['auth:web']);

