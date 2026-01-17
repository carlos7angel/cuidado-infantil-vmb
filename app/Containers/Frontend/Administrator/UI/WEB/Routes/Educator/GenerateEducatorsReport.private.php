<?php

use App\Containers\Frontend\Administrator\UI\WEB\Controllers\EducatorController;
use Illuminate\Support\Facades\Route;

Route::get('/educadores/reporte/excel', [EducatorController::class, 'generateEducatorsReport'])
    ->prefix(config('app.admin_prefix'))
    ->name('admin.educator.report.excel')
    ->middleware(['auth:web'])
    ->domain(parse_url(config('app.admin_url'), PHP_URL_HOST));
