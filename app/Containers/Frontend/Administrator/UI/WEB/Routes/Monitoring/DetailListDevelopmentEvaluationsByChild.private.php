<?php

use App\Containers\Frontend\Administrator\UI\WEB\Controllers\MonitoringController;
use Illuminate\Support\Facades\Route;

Route::get('evaluaciones-desarrollo/{development_evaluation_id}', [MonitoringController::class, 'detailListDevelopmentEvaluationsByChild'])
    ->prefix(config('app.admin_prefix'))
    ->name('admin.monitoring.detail-development-evaluation-by-child')
    ->middleware(['auth:web'])
    ->domain(parse_url(config('app.admin_url'), PHP_URL_HOST));

