<?php

use App\Containers\Frontend\Administrator\UI\WEB\Controllers\MonitoringController;
use Illuminate\Support\Facades\Route;

Route::get('/monitoreo/infante/{child_id}/incidentes', [MonitoringController::class, 'listIncidentsByChild'])
    ->prefix(config('app.admin_prefix'))
    ->name('admin.monitoring.list-incidents-by-child')
    ->middleware(['auth:web'])
    ->domain(parse_url(config('app.admin_url'), PHP_URL_HOST));
