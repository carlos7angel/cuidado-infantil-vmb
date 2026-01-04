<?php

use App\Containers\Monitoring\Attendance\UI\WEB\Controllers\StoreAttendanceController;
use Illuminate\Support\Facades\Route;

Route::post('attendances/store', StoreAttendanceController::class)
    ->middleware(['auth:web']);

