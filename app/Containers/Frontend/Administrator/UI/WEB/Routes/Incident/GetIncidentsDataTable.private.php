<?php

use App\Containers\Frontend\Administrator\UI\WEB\Controllers\IncidentController;
use Illuminate\Support\Facades\Route;

Route::post('/incidentes/json', [IncidentController::class, 'listJsonDataTable'])
    ->prefix(config('app.admin_prefix'))
    ->name('admin.incident.list_json_dt')
    ->middleware(['auth:web'])
    ->domain(parse_url(config('app.admin_url'), PHP_URL_HOST));

