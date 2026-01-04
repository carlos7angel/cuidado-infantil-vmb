<?php

use App\Containers\Monitoring\Attendance\UI\WEB\Controllers\EditAttendanceController;
use Illuminate\Support\Facades\Route;

Route::get('attendances/{id}/edit', EditAttendanceController::class)
    ->middleware(['auth:web']);

