<?php

use App\Containers\Frontend\Administrator\UI\WEB\Controllers\MonitoringController;
use Illuminate\Support\Facades\Route;

Route::get('/monitoreo/infante/{child_id}/resumen/exportar-excel', [MonitoringController::class, 'generateChildSummaryReport'])
    ->prefix(config('app.admin_prefix'))
    ->name('admin.monitoring.summarize-by-child.export-excel')
    ->middleware(['auth:web'])
    ->domain(parse_url(config('app.admin_url'), PHP_URL_HOST));

