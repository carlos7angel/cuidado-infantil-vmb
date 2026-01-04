<?php

use App\Containers\Frontend\Administrator\UI\WEB\Controllers\MonitoringController;
use Illuminate\Support\Facades\Route;

Route::get('/infantes/{child_id}/seguimiento-vacunacion', [MonitoringController::class, 'listVaccinationTrackingByChild'])
    ->prefix(config('app.admin_prefix'))
    ->name('admin.monitoring.list-vaccination-tracking-by-child')
    ->middleware(['auth:web'])
    ->domain(parse_url(config('app.admin_url'), PHP_URL_HOST));

