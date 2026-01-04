<?php

use App\Containers\Frontend\Administrator\UI\WEB\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;

Route::get('/reporte-asistencia', [AttendanceController::class, 'report'])
    ->prefix(config('app.admin_prefix'))
    ->name('admin.attendance.report')
    ->middleware(['auth:web'])
    ->domain(parse_url(config('app.admin_url'), PHP_URL_HOST));

