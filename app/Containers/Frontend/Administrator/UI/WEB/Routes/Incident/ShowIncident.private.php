<?php

use App\Containers\Frontend\Administrator\UI\WEB\Controllers\IncidentController;
use Illuminate\Support\Facades\Route;

Route::get('/incidentes/{incident_id}/detalle', [IncidentController::class, 'show'])
    ->prefix(config('app.admin_prefix'))
    ->name('admin.incident.show')
    ->middleware(['auth:web'])
    ->domain(parse_url(config('app.admin_url'), PHP_URL_HOST));

