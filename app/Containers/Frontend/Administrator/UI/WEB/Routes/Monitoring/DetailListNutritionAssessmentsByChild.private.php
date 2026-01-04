<?php

use App\Containers\Frontend\Administrator\UI\WEB\Controllers\MonitoringController;
use Illuminate\Support\Facades\Route;

Route::get('evaluaciones-nutricionales/{nutritional_assessment_id}', [MonitoringController::class, 'detailListNutritionAssessmentsByChild'])
    ->prefix(config('app.admin_prefix'))
    ->name('admin.monitoring.detail-nutritional-assessment-by-child')
    ->middleware(['auth:web'])
    ->domain(parse_url(config('app.admin_url'), PHP_URL_HOST));


