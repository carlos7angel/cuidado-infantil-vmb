<?php

use App\Containers\Monitoring\Attendance\UI\WEB\Controllers\CreateAttendanceController;
use Illuminate\Support\Facades\Route;

Route::get('attendances/create', CreateAttendanceController::class)
    ->middleware(['auth:web']);

