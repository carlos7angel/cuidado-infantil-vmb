<?php

use App\Containers\Monitoring\NutritionalAssessment\UI\WEB\Controllers\EditNutritionalAssessmentController;
use Illuminate\Support\Facades\Route;

Route::get('nutritional-assessments/{id}/edit', EditNutritionalAssessmentController::class)
    ->middleware(['auth:web']);

