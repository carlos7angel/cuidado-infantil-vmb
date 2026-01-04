<?php

use App\Containers\Frontend\Administrator\UI\WEB\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;

Route::get('/reporte-asistencia/descargar', [AttendanceController::class, 'generateAttendanceXlsReport'])
    ->prefix(config('app.admin_prefix'))
    ->name('admin.attendance.report.download')
    ->middleware(['auth:web'])
    ->domain(parse_url(config('app.admin_url'), PHP_URL_HOST));

