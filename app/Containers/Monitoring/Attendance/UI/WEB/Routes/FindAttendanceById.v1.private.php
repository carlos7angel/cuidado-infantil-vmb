<?php

use App\Containers\Monitoring\Attendance\UI\WEB\Controllers\FindAttendanceByIdController;
use Illuminate\Support\Facades\Route;

Route::get('attendances/{id}', FindAttendanceByIdController::class)
    ->middleware(['auth:web']);

