<?php

use App\Containers\Frontend\Administrator\UI\WEB\Controllers\IncidentController;
use Illuminate\Support\Facades\Route;

Route::patch('/incidentes/{incident_id}/estado', [IncidentController::class, 'updateStatus'])
    ->prefix(config('app.admin_prefix'))
    ->name('admin.incident.update_status')
    ->middleware(['auth:web'])
    ->domain(parse_url(config('app.admin_url'), PHP_URL_HOST));

