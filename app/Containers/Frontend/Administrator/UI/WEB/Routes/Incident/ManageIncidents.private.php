<?php

use App\Containers\Frontend\Administrator\UI\WEB\Controllers\IncidentController;
use Illuminate\Support\Facades\Route;

Route::get('/incidentes', [IncidentController::class, 'manage'])
    ->prefix(config('app.admin_prefix'))
    ->name('admin.incident.manage')
    ->middleware(['auth:web'])
    ->domain(parse_url(config('app.admin_url'), PHP_URL_HOST));

